<?php
//
//  PowerTemplate.php
//  PowerTemplate
//
//  Created by Jonathan Younger <jyounger@caedic.com> on Tue Oct 22 2002.
//  Copyright (c) 2002 Jonathan Younger <jyounger@caedic.com>. All rights reserved.
 
function mapDate($dateFormat) {
    # Map the template language data formats to the php date formats
    $dateFormatMapping = array();
    $dateFormatMapping["%%"] = "%";
    $dateFormatMapping["%a"] = "D";
    $dateFormatMapping["%A"] = "l";
    $dateFormatMapping["%b"] = "M";
    $dateFormatMapping["%B"] = "F";
    $dateFormatMapping["%c"] = "";
    $dateFormatMapping["%d"] = "d";
    $dateFormatMapping["%e"] = "j";
    $dateFormatMapping["%F"] = "";
    $dateFormatMapping["%H"] = "H";
    $dateFormatMapping["%I"] = "h";
    $dateFormatMapping["%j"] = "z";
    $dateFormatMapping["%m"] = "m";
    $dateFormatMapping["%M"] = "i";
    $dateFormatMapping["%p"] = "A";
    $dateFormatMapping["%S"] = "s";
    $dateFormatMapping["%w"] = "w";
    $dateFormatMapping["%x"] = "";
    $dateFormatMapping["%X"] = "";
    $dateFormatMapping["%y"] = "y";
    $dateFormatMapping["%Y"] = "Y";
    $dateFormatMapping["%Z"] = "T";
    $dateFormatMapping["%z"] = "Z";
    
    foreach ($dateFormatMapping as $fromFormat => $toFormat) {
        $dateFormat = str_replace($fromFormat, $toFormat, $dateFormat);
    }    
    return $dateFormat;    
}

class PowerTemplate {
    var $_template = "";
    var $_openingTag = "[";
    var $_closingTag = "]";
    var $_templateVariables = array();
    
    function PowerTemplate($template="") {
        $this->_template = $template;    
    }
    
    function getTemplate() { return $this->_template; }
    function setTemplate($value) { $this->_template = $value; }
    
    function loadWithContentsOfFile($filename) {
        if (!file_exists($filename)) { return "IOError, No such file or directory: \"$filename\""; } 
        
        $fd = fopen ($filename, "r");
        $fileContent="";
        while (!feof ($fd)) {
            $fileContent .= fgets($fd, 4096);
        }
        fclose ($fd);        
        
        $this->_template = $fileContent;
        return True;
    }
        
        
    function assign($variableName, $variableValue) {
        # Assign the variable to the template variables dictionary
        $this->_templateVariables[$variableName] = $variableValue;
    }
    
    function parse() {
        # Parse the template with the contents of the template variable
        $this->_template = $this->_parseFragment($this->_template);
        return $this->_template;
    }
    
    function _parseFragment($fragment, $nested=False, $loopPass=1) {
        if (!$nested) {
            # Replace escaped tag delimiters with an internal  tag that will be replaced later
            $fragment = str_replace("[[]", "~__PowerTemplateLeftBracket__~", $fragment);
            $fragment = str_replace("[]]", "~__PowerTemplateRightBracket__~", $fragment);
        }
        
        $currentPosition = 0;

        # Loop through the text until no more opening tags are found
        while (is_integer($currentPosition)) {
            $currentPosition = strpos($fragment, $this->_openingTag, $currentPosition);
            
            # If we found an opening tag continue on otherwise we are done parsing
            if (is_integer($currentPosition)) {
                # Save the currentPosition of the opening tag so that we can reference it later
                $openingTagPosition = $currentPosition;

                # Find the closing tag. If it is not found then exit with an error
                $closingTagPosition = strpos($fragment, $this->_closingTag, $openingTagPosition);
                if (!is_integer($closingTagPosition)) { return "SyntaxError, Missing closing tag for opening tag at position $currentPosition"; }
                $tagLength = $closingTagPosition - $openingTagPosition - 1;
                
                # Get the text inside of the opening and closing tags and normalize the spaces in between the words and explode the components into an array
                $tag = explode(" ", trim(preg_replace('/\s+/m', " ", substr($fragment, $openingTagPosition+1, $tagLength))));
                
                # Get the tag type and name of the tag 
                if (count($tag) > 1) {
                    $tagType = $tag[0];
                    $tagName = $tag[1];
                } elseif ($tag[0] == "date") {
                    $tagType = "date";
                    $tagName = "date";
                } else {
                    $tagType = "value";
                    $tagName = $tag[0];
                }

                # Begin processing the template based upon what kind of tag was found
                switch ($tagType) {
                    case "value":
                        # A value tag was found so we'll retrieve the value 
                        # from a template variable with the name of the tag                    
                        $value = null;
                        
                        # If the tag can be found directly in the template variables then get that otherwise see
                        # if it can be found inside of a dictionary in the template variables
                        if (array_key_exists($tagName, $this->_templateVariables)) {
                            # The value was directly in the template variables
                            $value = $this->_templateVariables[$tagName];
                        } else {
                            # Try to determine if it is in a dictionary
                            if (is_integer(strpos($tagName, "."))) {
                                # It appears it could be in a dictionary. Get the name of the parent dictionary and the
                                # name of the child variable inside the parent dictionary
                                $templateVariable = explode(".", $tagName);
                                $templateVariableName = $templateVariable[0];
                                $templateVariableIndexName = $templateVariable[1];
                                
                                # Is the name of the parent dictionary in the template variables?
                                if (array_key_exists($templateVariableName, $this->_templateVariables)) {
                                    # Is the name of the child variable in the parent dictionary?
                                    if (array_key_exists($templateVariableIndexName, $this->_templateVariables[$templateVariableName])) {
                                        # The value is the child variable in the parent dictionary
                                        $value = $this->_templateVariables[$templateVariableName][$templateVariableIndexName];
                                    }
                                }
                            }
                        }
                        
                        # If a value was found then replace that tag in template with the value
                        # Otherwise exit with an error 
                        if (!is_null($value)) {
                            $fragment = substr($fragment, 0, $openingTagPosition) . $value . substr($fragment, $closingTagPosition + 1);
                        } elseif ($loopPass == 2) {
                            return "KeyError, Missing data for key [$tagName]";
                        } else { 
                            $currentPosition = $closingTagPosition + 1;
                            continue 2;                            
                        }
                        break;
                    case "if":
                        # An if tag was found so we'll evaluate the expression and act accordingly
                        
                        # Get the name of the endif and else tags so that we can find them later
                        $endifTag = "[endif $tagName]";
                        $elseTag = "[else $tagName]";
                        
                        # Get the starting position of the endif tag. If there isn't one then
                        # exit with an error                        
                        $endifStartPosition = strpos($fragment, $endifTag, $closingTagPosition);
                        if (!is_integer($endifStartPosition)) { return "SyntaxError, Missing endif tag for $tagName"; }
                        
                        # Get the starting and ending positions of the endif and else tags
                        $endifEndPosition = $endifStartPosition + strlen($endifTag);
                        $elseStartPosition = strpos($fragment, $elseTag, $closingTagPosition);
                        $elseEndPosition = $elseStartPosition + strlen($elseTag);
                        
                        # Evaluate the expression and act accordingly
                        $isExpressionTrue = $this->_evaluateIfStatement($tag);
                        if (preg_match("/SyntaxError.*/", $isExpressionTrue) || preg_match("/KeyError.*/", $isExpressionTrue)) { return $isExpressionTrue; }
                        
                        if ($isExpressionTrue) {
                            # The expression is true so we'll get the positions
                            # of the true content area                            
                            $contentAreaStartPosition = $closingTagPosition + 1;
                            if (is_integer($elseStartPosition)) {
                                $contentAreaEndPosition = $elseStartPosition;
                            } else {
                                $contentAreaEndPosition = $endifStartPosition;
                            }
                        } else {
                            # The expression is false so we'll get the positions
                            # of the false content area or set the positions
                            # to -1 if there isn't a false content area                            
                            if (is_integer($elseStartPosition)) {
                                $contentAreaStartPosition = $elseEndPosition;
                                $contentAreaEndPosition = $endifStartPosition;
                            } else {
                                $contentAreaStartPosition = -1;
                                $contentAreaEndPosition = -1;
                            }
                        }
                        
                        # If there is an area to process then get it otherwise the area is blank
                        if (($contentAreaStartPosition != -1) && ($contentAreaEndPosition != -1)) {
                            $contentArea = substr($fragment, $contentAreaStartPosition, $contentAreaEndPosition - $contentAreaStartPosition);
                        } else {
                            $contentArea = "";
                        }
                        
                        # Update the template with the content area
                        $fragment = substr($fragment, 0, $openingTagPosition) . $contentArea . substr($fragment, $endifEndPosition + 1);
                        break;
                    case "each":
                        # An each tag was found so we'll loop through and recursively parse the content
                        # inside of the block                    
                        
                        # Get the name of the endeach tag so we can find it later
                        $endeachTag = "[endeach $tagName]";
                        
                        # Get the starting position of the endeach tag or exit with an error if there isn't one
                        $endeachStartPosition = strpos($fragment, $endeachTag, $closingTagPosition);
                        if (!is_integer($endeachStartPosition)) { return "SyntaxError, Missing endeach tag for $tagName"; }
                        
                        # Get the ending position of the endeach tag
                        $endeachEndPosition = $endeachStartPosition + strlen($endeachTag);
                        
                        # Get the content area for the each block
                        $contentAreaStartPosition = $closingTagPosition + 1;
                        $contentAreaEndPosition = $endeachStartPosition;
                        $contentArea = substr($fragment, $contentAreaStartPosition, $contentAreaEndPosition - $contentAreaStartPosition);
                        
                        # Get the positions and the name of the variable we'll use inside the block
                        $eachValueName = $tag[2];
                        
                        # Get the position and name of the key inside of the template variables
                        # If the key can't be found or if it is not a list type then
                        # exit with an error                        
                        $listKey = $tag[3];
                        if (!array_key_exists($listKey, $this->_templateVariables)) { return "KeyError, Missing data for key [$listKey]"; }
                        if (!is_array($this->_templateVariables[$listKey])) { return "TypeError, $listKey is not an array"; }
                        
                        # Initialize the content variable that will hold the looped content
                        $eachContent = "";
                        
                        # Loop through the list using the list key, store the list item and list item index in the template variables
                        # and recursively parse the content from the each block                        
                        foreach($this->_templateVariables[$listKey] as $xIndex => $x) {
                            # Put the current item and current item index into the template variables so that it can be used
                            # inside of the block content                            
                            $eachValueNameIndex = $eachValueName . "_index";
                            $this->_templateVariables[$eachValueNameIndex] = $xIndex;
                            $this->_templateVariables[$eachValueName] = $x;
                            
                            # Recursively call the parse function passing in the current each block content area
                            $eachContent .= $this->_parseFragment($contentArea, True);
                            
                            # Remove the current item and item index from the template variables as
                            # they are no longer needed                            
                            unset($this->_templateVariables[$eachValueName]);
                            unset($this->_templateVariables[$eachValueNameIndex]);
                        }
                        
                        # Assign the results of the looped content to the template
                        $fragment = substr($fragment, 0, $openingTagPosition) . $eachContent . substr($fragment, $endeachEndPosition);
                        break;
                    case "include":
                        # An include tag was found so we'll include the contents of the file or exit and error out
                        # if the file does not exists                    
                        if (array_key_exists($tag[1], $this->_templateVariables)) {
                            $includeFileName = $this->_templateVariables[$tag[1]];
                        } else {
                            $includeFileName = $tag[1];
                        }
                        
                        if (!file_exists($includeFileName)) { return "IOError, No such file or directory: \"$IncludeFileName\""; }
                        $fd = fopen ($includeFileName, "r");
                        $includeContent="";
                        while (!feof ($fd)) {
                            $includeContent .= fgets($fd, 4096);
                        }
                        fclose ($fd);
                        
                        # Assign the contents of the file to the template
                        $fragment = substr($fragment, 0, $openingTagPosition) . $includeContent . substr($fragment, $closingTagPosition + 1);
                        break;
                    case "comment":
                        # A comment tag was found so we'll just remove the whole tag from the template
                        $fragment = substr($fragment, 0, $openingTagPosition) . substr($fragment, $closingTagPosition + 1);
                        break;
                    case "date":
                        # A date tag was found so we'll format the current date if a format
                        # wasn't provided                    
                        
                        # Get the date format
                        if (count($tag) > 1) {
                            $dateFormat = mapDate(join(" ", array_slice($tag, 1)));
                        } else {
                            $dateFormat = mapDate("%a %m/%d/%Y %I:%M:%S %p");                        
                        }
                        
                        # Get the formatted date and assign it to the template
                        $date = date($dateFormat);
                        $fragment = substr($fragment, 0, $openingTagPosition) . $date . substr($fragment, $closingTagPosition + 1);
                        break;
                    case "datevalue":
                        # A datevalue tag was found so we'll get the value from the template variables
                        # and assign it the specified format or a default format if necessary
                    
                        # If the value is in the template variables then retrieve it and format it
                        # otherwise exit and error out                    
                        if (array_key_exists($tagName, $this->_templateVariables)) {
                            # Get the date format
                            if (count($tag) > 2) {
                                $dateFormat = mapDate(join(" ", array_slice($tag, 2)));
                            } else {
                                $dateFormat = mapDate("%a %m/%d/%Y %I:%M:%S %p");
                            }
                            
                            # Get the formatted date and assign it to the template
                            $date = date($dateFormat, $this->_templateVariables[$tagName]);                            
                            $fragment = substr($fragment, 0, $openingTagPosition) . $date . substr($fragment, $closingTagPosition + 1);
                        } else {
                            # Error out
                            return "KeyError, Missing data for key [$tagName]";
                        }
                        break;
                    case "set":
                        # A set tag was found. Set a template variable to the result
                        # of the expression                    
                        
                        # Get the result of the expression
                        $result = $this->_evaluateSetExpression($tag);
                        
                        # Error out if the result is an error
                        if (preg_match("/SyntaxError.*/", $result)) { return $result; }
                        
                        # If there is a result then set the template variable to it
                        if (!is_null($result)) {
                            $this->_templateVariables[$tagName] = $result;
                        }
                        
                        # Remove the set tag from the template
                        $fragment = substr($fragment, 0, $openingTagPosition) . substr($fragment, $closingTagPosition + 1);
                        break;
                        
                    case "loop":
                        # A loop tag was found. Loop the content from start to end and by the designated
                        # incrementor
                        
                        # Get the name of the endloop tag so that we can find it later
                        $endloopTag = "[endloop $tagName]";
                        
                        # Get the starting position of the endloop tag or exit with an error if there isn't one 
                        $endloopStartPosition = strpos($fragment, $endloopTag, $closingTagPosition);
                        if (!is_integer($endloopStartPosition)) { return "SyntaxError, Missing endloop tag for $tagName"; }
                        
                        # Get the ending position of the endloop tag 
                        $endloopEndPosition = $endloopStartPosition + strlen($endloopTag);
                        
                        # Get the content area for the loop block
                        $contentAreaStartPosition = $closingTagPosition + 1;
                        $contentAreaEndPosition = $endloopStartPosition;
                        $contentArea = substr($fragment, $contentAreaStartPosition, $contentAreaEndPosition - $contentAreaStartPosition);
                        
                        # Get the loop parameters or error out if the parameters are incorrect
                        if (count($tag) != 6) { return "SyntaxError, Incorrect number of loop parameters [$tag]"; }
                        
                        # Get the loop value name 
                        $loopValueName = $tag[2];
                        
                        # Get the start value and error out if it is not an integer
                        if (is_numeric($tag[3])) {
                            $loopStartValue = (int)$tag[3];
                        } else {
                            return "ValueError, <start value> must be an integer instead of \"" . $tag[3] . "\"";
                        }
                        
                        # Get the end value and error out if it is not an integer
                        if (is_numeric($tag[4])) {
                            $loopEndValue = (int)$tag[4];
                        } else {
                            return "ValueError, <end value> must be an integer instead of \"" . $tag[4] . "\"";
                        }
                        
                        # Get the step value and error out if it is not an integer
                        if (is_numeric($tag[5])) {
                            $loopStepValue = (int)$tag[5];
                        } else {
                            return "ValueError, <step value> must be an integer instead of \"" . $tag[5] . "\"";
                        }

                        # Initialize the content variable that will hold the looped content
                        $loopContent = "";
                        
                        
                        if ($loopStartValue < $loopEndValue) {
                            # Loop incrementally
                            # Loop starting at the start value, ending at the end value and step by 
                            # the step value. Assign the current loop value to the loopValueName template variable
                            for ($loopValue = $loopStartValue; $loopValue <= $loopEndValue; $loopValue += $loopStepValue) {
                                
                                # Put the current loop value into the template variables so that it can be used
                                # inside of the block content
                                $this->_templateVariables[$loopValueName] = $loopValue;
        
                                # Recursively call the parse function passing in the current each block content area
                                $loopContent .= $this->_parseFragment($contentArea, True);
                                
                                # Remove the current item and item index from the template variables as
                                # they are no longer needed
                                unset($this->_templateVariables[$loopValueName]);
                            }
                        } else {
                            # Loop decrementally
                            # Loop starting at the start value, ending at the end value and step by 
                            # the step value. Assign the current loop value to the loopValueName template variable
                            for ($loopValue = $loopStartValue; $loopValue >= $loopEndValue; $loopValue += $loopStepValue) {
                                
                                # Put the current loop value into the template variables so that it can be used
                                # inside of the block content
                                $this->_templateVariables[$loopValueName] = $loopValue;
        
                                # Recursively call the parse function passing in the current each block content area
                                $loopContent .= $this->_parseFragment($contentArea, True);
                                
                                # Remove the current item and item index from the template variables as
                                # they are no longer needed
                                unset($this->_templateVariables[$loopValueName]);
                            }
                        }                            
                            
                        # Assign the results of the looped content to the template
                         $fragment = substr($fragment, 0, $openingTagPosition) . $loopContent . substr($fragment, $endloopEndPosition);
                        break;                        
                    default:
                        # Data that is not a tag was found so we'll just increment the currentPosition
                        $currentPosition++;
                        break;
                }
            }
        }

        if ((!$nested) && ($loopPass == 1)) {
            $fragment = $this->_parseFragment($fragment, True, 2);
        }
        
        if (!$nested) {
            # Replace the internal opening and closing tag delimiters with the real thing that should be in the template
            $fragment = str_replace("~__PowerTemplateLeftBracket__~", "[", $fragment);
            $fragment = str_replace("~__PowerTemplateRightBracket__~", "]", $fragment);        
        
            # Return remove extra blank lines and return the parsed template
            return preg_replace('/\s+$/m', "", $fragment);
        } else {
            return $fragment;
        }
    }
    
    function _evaluateSetExpression($tag) {
        # Parse a tag for an expression and return the result
        # or error out if necessary
        
        # Get the expression from the tag        
        $expression = join(" ", array_slice($tag, 2));
        
        # If the expression is a variable in the template variables then return it
        if (array_key_exists($expression, $this->_templateVariables)) { return $this->_templateVariables[$expression]; }
        
        # If the expression isn't blank then continue otherwise error out
        if ($expression != "") {
            # If the expression is joining values together then return the joined string
            if (is_integer(strpos($expression, ":="))) {
                # Break the expression apart into the seperate pieces that will be joined together
                $expressionArray = explode(":=", $expression);
                
                # Loop through the individual expression items of the expression and act accordingly on each one
                foreach($expressionArray as $expressionItemIndex => $expressionItem) {
                    if (array_key_exists(trim($expressionItem), $this->_templateVariables)) {
                        $expressionArray[$expressionItemIndex] = $this->_templateVariables[trim($expressionItem)];
                    } else {
                        $expressionArray[$expressionItemIndex] = str_replace("\"", "", trim($expressionItem));
                    }
                }
                
                # Join the expression items back together and return the result
                return join("", $expressionArray);            
            
            # If the expression is a string literal then return the string literal
            } elseif (($expression[0] == "\"") && ($expression[strlen($expression)-1] == "\"")) {
                return substr($expression, 1, -1);
            } else {
                # The expression must be a mathematical expression

                # Pad special characters with spaces so that we can get to the values later
                $characterArray = array("(", ")", "*", "/", "+", "-", "%");
                foreach($characterArray as $character) {
                    $expression = str_replace($character, " $character ", $expression);
                }
        
                # Separate the expression into individual expression items                
                $expressionArray = explode(" ", $expression);

                # Initialize the expression that will evaluated by the eval function
                $evalExpression = "";
                
                # Loop through the individual expression items of the expression and act accordingly on each one
                foreach($expressionArray as $expressionItem) {
                    if (array_key_exists($expressionItem, $this->_templateVariables)) {
                        # If the expression item is a template variable then append the value
                        # of the variable to the eval expression
                        $evalExpression .= $this->_templateVariables[$expressionItem] . " ";
                    } elseif ($expressionItem == $tag[1]) {
                        # If the expression item is the same name as the tag
                        # then initialize a template variable with the name of
                        # the expression item and append the initialized value to the
                        # eval expression                        
                        $this->_templateVariables[$expressionItem] = 0;
                        $evalExpression .= "0 ";
                    } else {
                        # The expression item is static and can be appended to the eval expression
                        $evalExpression .= "$expressionItem ";
                    }
                }
                
                # Try to eval the expression and return the result. Error out if the expression
                # can not be evalulated                
                eval("\$returnValue = ($evalExpression);");
                if (is_null($returnValue) || $returnValue=="") {
                    return "SyntaxError, Invalid set expression $evalExpression";
                } else {
                    return $returnValue;
                }
            }
        } else {
            # The expression is blank so error out
            return "SyntaxError, Invalid set expression $expression";
        }
    }
    
    function _evaluateIfStatement($tag) {
        # Parse a tag for a conditional statement and return
        # a Boolean or an error accordingly
		
		$isExpressionTrue = false;
        
        # Get the left hand side of the statement which _must_ be
        # a template variable or the values "-s" or "-ns"        
        $leftTestValue = $tag[2];
        
        if (($leftTestValue != "-s") && ($leftTestValue != "-ns")) {
            # If the left hand statement is not "-s" or "-ns" then check to
            # see if it is a template variable. If it isn't then error out            
            if (!array_key_exists($leftTestValue, $this->_templateVariables)) { return "KeyError, Missing data for key [$leftTestValue]"; }
            
            # Get the value of the left hand statement from the template variables
            $leftTestValue = $this->_templateVariables[$leftTestValue];
            
            # Get the test conditional from the tag
            $test = $tag[3];
            
            # Get the right hand side statement from the tag
            $rightTestValue = $tag[4];
            
            # If the right hand side statement is a template variable
            # then get the value of the template variable            
            if (array_key_exists($rightTestValue, $this->_templateVariables)) {
                $rightTestValue = $this->_templateVariables[$rightTestValue];
            }
            switch ($test) {
                case ">":
                case "<":
                case ">=":
                case "<=":
                case "==":
                case "!=":
                    # The test conditional is valid so create an expression to be evaluated
                    # If the left hand and right hand side values are numeric then the expression
                    # is a mathematical one otherwise the expression is a string  one                
                    if ((is_numeric($leftTestValue)) && (is_numeric($rightTestValue))) {
                        $expression = "if ($leftTestValue $test $rightTestValue) { \$isExpressionTrue = true; }";
                    } else {
                        $expression = "if ('$leftTestValue' $test '$rightTestValue') { \$isExpressionTrue = true; }";
                    }
                    
                    # Return the boolean result of the evaluation
                    eval($expression);
                    return $isExpressionTrue;
                    break;
                default:
                    # The test is invalid so error out
                    return "SyntaxError, Invalid test \"$test\"";
            }
        } else {
            # The left hand side statement is "-s" or "-ns" so
            # we'll be testing the presense of a template variable
            
            # Get the name of the template variable we'll be testing            
            $rightTestValue = $tag[3];
            
            # If the we are testing for the presense "-s" of the template
            # variable then return if the variable exists
            # other wise if we are testing that the variable does not
            # exist then return that            
            switch ($leftTestValue) {
                case "-s":
                    return @array_key_exists($rightTestValue, $this->_templateVariables);
                    break;
                case "-ns":
                    return !@array_key_exists($rightTestValue, $this->templateVariables);
                    break;
            }
        }
        return false;
    }
}
?>
