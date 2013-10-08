<?php
/**
 * Easy Template class that replaces tags in html file like <!--TagName --> with any value
 * For additional HTML tag support and functionality, use the TemplateHTML class instead.
 *
 * Mar 13, 2007 - Initial Release
 * Mar 27, 2007 - Separated HTML Support into Extended Class
 *
 * @version 2.5
 * @author Jeff L. Williams
 */
class Template {

    /**
     * Contains the template page HTML
     *
     * @var string Contains the HTML for the template
     */
    private $page;

    /**
     * Constructor method
     *
     * @param string $templateFile the filename for the template
     */
    public function __construct($templateFile = '')
    {
        if (strlen($templateFile) > 0) {
            $this->loadPage($templateFile);
        }
    }

    /**
     * Get a POST or GET value by a form element name
     * (STATIC method - use Template::getFormValue)
     *
     * @param string  $name The name of the form element sent by POST or GET
     * @return string
     */
    static function getFormValue($name)
    {
        if (isset($_POST[$name]))
        {
            return $_POST[$name];
        } else {
            if (isset($_GET[$name]))
            {
                return $_GET[$name];
            } else {
                return '';
            }
        }
    }

    /**
     * Returns the template HTML
     *
     * @return string Processed HTML
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Determines if a specified comment tag exists
     *
     * @param string  $tagName The name of the comment style tag
     * @return TRUE if exists, FALSE if not
     */
    public function hasTag($tagName) {
        // Grab the opening HTML tag using regular expressions
        $pattern = '/<!--' . $tagName . '\\s-->/';

        // If we find a match... (store the opening HTML tag)
        if (preg_match ($pattern, $this->$page)) {

            // Success
            return true;

        } else { // We couldn't find the tag

            // No tag found by that name
            return false;
        }
    }

    /**
     * Loads a template HTML from a file
     *
     * @param string $templateFile the filename for the template
     */
    public function loadPage($templateFile) {
        if ($templateFile == '') {
            // Do nothing
        } elseif (!file_exists($templateFile)) {
            die('Template file does not exists: ' . $templateFile);
        } else {
            $this->setPage(join("", file($templateFile)));
        }
    }

    /**
     * Removes all tags from the document
     * WARNING: There is no way to differentiate between the tags and
     * HTML comments since the tags actually are HTML comments, so all
     * comments with <!-- --> tags will be removed using this method!
     *
     */
    public function removeAllTags()
    {
        // Remove all HTML comments using a regular expression
        $this->page = preg_replace('/<!--(.|\s)*?-->/', '', $this->page);
    }

    /**
     * Removes a tag from the page
     *
     * @param string $tagName The name of the tag to remove
     */
    public function removeTag($tagName)
    {
        // Call replaceTag without a value
        $this->replaceTag($tagName);
    }

    /**
     * Replaces a tag embedded in the template with a string
     *
     * @param string  $tagName The name of the tag <!--tagName -->
     * @param string  $content The content to insert
     * @param boolean $fixHTML Determines if the content should be formatted
     * @param boolean $stripSlashes Encodes the contect
     * @param boolean $keepTag If true, the tag will stay in the template
     */
    public function replaceTag($tagName, $content = '', $fixHTML = false, $stripSlashes = false, $keepTag = false)
    {
        if ($tagName == '') {
            die('Target tag parameter not passed to replaceInPage method');
        } else {
            if ($stripSlashes) {
                $content = stripslashes($content);
            }
            if ($fixHTML) {
                $content = htmlentities($content);
            }
            if($keepTag == true) {
                $tag = '<!--' . $tagName . ' -->';
            } else {
                $tag = '';
            }
        }

        $this->setPage(str_replace('<!--' . $tagName . ' -->', $content.$tag, $this->getPage()));
    }

    /**
     * Replaces a tag embedded in the template with data from a file
     *
     * @param string  $tagName The name of the tag <!--tagName -->
     * @param string  $fileName The name of the file containing the data
     * @param boolean $fixHTML Determines if the content should be formatted
     * @param boolean $stripSlashes Encodes the contect
     * @param boolean $keepTag If true, the tag will stay in the template
     */
    public function replaceTagFromFile($tagName, $fileName, $fixHTML = false, $stripSlashes = false, $keepTag = false)
    {
        if (!file_exists($fileName)) {
            die('File does not exists: ' . $fileName);
        } else {
            if ($tagName == '') {
                die('Target tag parameter not passed to replaceInPage method');
            } else {
                $content = join("", file($fileName));

                if ($stripSlashes) {
                    $content = stripslashes($content);
                }
                if ($fixHTML) {
                    $content = htmlentities($content);
                }
                if($keepTag == true) {
                    $tag = '<!--' . $tagName . ' -->';
                } else {
                    $tag = '';
                }

                $this->setPage(str_replace('<!--' . $tagName . ' -->', $content.$tag, $this->getPage()));
            }
        }
    }

    /**
     * Replaces a tag embedded in the template with data from web page
     *
     * @param string  $tagName The name of the tag <!--tagName -->
     * @param string  $url The URL containing the web data
     * @param boolean $fixHTML Determines if the content should be formatted
     * @param boolean $stripSlashes Encodes the contect
     * @param boolean $keepTag If true, the tag will stay in the template
     */
    public function replaceTagFromWeb($tagName, $url, $fixHTML = false, $stripSlashes = false, $keepTag = false)
    {
        $content = file_get_contents($url);

        if (!$content) {
            die('Cannot find site: ' . $url);
        } else {
            if ($tagName == '') {
                die('Target tag parameter not passed to replaceInPage method');
            } else {
                if ($stripSlashes) {
                    $content = stripslashes($content);
                }
                if ($fixHTML) {
                    $content = htmlentities($content);
                }
                if($keepTag == true) {
                    $tag = '<!--' . $tagName . ' -->';
                } else {
                    $tag = '';
                }

                $this->setPage(str_replace('<!--' . $tagName . ' -->', $content, $this->getPage()));
            }
        }
    }

    /**
     * Sets the template HTML
     *
     * @param string $html Template HTML
     */
    public function setPage($html)
    {
        $this->page = $html;
    }

    /**
     * Shows the template HTML
     *
     */
    public function showPage()
    {
        echo $this->getPage();
    }

    /**
     * Compares two strings for a selected OPTION item
     *
     * @param string  $compare1 Text to compare
     * @param string  $compare2 Text to compare
     * @param boolean $caseSensitive Case-sensitive compare
     * @return string ' selected="selected"' if match
     */
    private static function compareHTMLSelected($compare1, $compare2, $caseSensitive) {
        if ($caseSensitive) {
            if ($compare1 == $compare2) {
                return ' selected="selected"';
            } else {
                return '';
            }
        } else {
            if (strtoupper(trim($compare1)) == strtoupper(trim($compare2))) {
                return ' selected="selected"';
            } else {
                return '';
            }
        }
    }

    /**
     * Creates OPTION lists for a SELECT from an array
     *
     * @param array   $array An array containing numbered or associated arrays (rows)
     * @param string/integer $displayColumn The column in the arrays that contains the displayed text
     * @param string  $valueColumn The column in the arrays that contains the form return values
     * @param string  $selectedValue The default selected item in the list
     * @param boolean $caseSensitive TRUE if the selected item is chosen using case sensitivity
     * @param integer $indent The number of tabs to indent
     * @return boolean Either the HTML string or FALSE for failure
     */
    public static function createHTMLSelectData($array, $displayColumn, $valueColumn = '', $selectedValue = '', $caseSensitive = false, $indent = 0) {

        // Setup some formatting
        If ($indent == 0) {
            $html= '';
            $tab = '';
            $nl= '';
        } else {
            $html = "\n";
            $tab = str_repeat("\t", $indent);
            $nl = "\n";
        }

        // Are we using separate values?
        if (strlen(trim($valueColumn)) == 0) {
            $hasValue = false;
        } else {
            $hasValue = true;
        }

        // Loop through the rows
        foreach ($array as $row) {
            $html .= $tab . '<option ';

            if ($hasValue) {
                $html .= ' value="' . htmlspecialchars($row[$valueColumn]) . '"' .
                         self::compareHTMLSelected($selectedValue, $row[$valueColumn], $caseSensitive) .
                         '>' . htmlspecialchars($row[$displayColumn]) . "</option>" . $nl;
            } else {
                $html .= self::compareHTMLSelected($selectedValue, $row[$displayColumn], $caseSensitive) .
                         '>' . htmlspecialchars($row[$displayColumn]) . "</option>" . $nl;
            }
        }

        return $html;
    }

    /**
     * Creates table data from an array (not including the open and ending <TABLE> tags)
     *
     * @param array   $array An array containing numbered or associated arrays (rows)
     * @param boolean $showAssocArrayColumnTitles TRUE if you would like to display heading titles.
     *                NOTE: This only works with associated arrays that contain key values
     * @param integer $indent The number of tabs to indent
     * @param string  $optionalRowHeadingTags
     * @param string  $optionalCellHeadingTags
     * @param string  $optionalRowTags
     * @param string  $optionalCellTags
     * @param string  $optionalRowTags2
     * @param string  $optionalCellTags2
     * @return boolean Either the HTML string or FALSE for failure
     */
    public static function createHTMLTableData($array, $showAssocArrayColumnTitles = true, $indent = 0,
        $optionalRowHeadingTags = '', $optionalCellHeadingTags = '',
        $optionalRowTags = '', $optionalCellTags = '',
        $optionalRowTags2 = '', $optionalCellTags2 = '') {

        // If we have records to show
        if (count($array) > 0) {

            // Setup some formatting variables
            If ($indent == 0) {
                $html= '';
                $tabRow= '';
                $tabCell= '';
                $nl= '';
            } else {
                $html = "\n";
                $tabRow = str_repeat("\t", $indent);
                $tabCell = str_repeat("\t", $indent + 1);
                $nl = "\n";
            }

            // Style information for the header with field names
            // (ONLY WORKS WITH ASSOCIATIVE ARRAYS)
            if (strlen($optionalRowHeadingTags) > 0) {
                $trHeading = "<tr " . $optionalRowHeadingTags . ">";
            } else {
                $trHeading = "<tr>";
            }
            if (strlen($optionalCellHeadingTags) > 0) {
                $tdHeading = "<td " . $optionalCellHeadingTags . ">";
            } else {
                $tdHeading = "<td>";
            }

            // Style information for the rows
            if (strlen($optionalRowTags) > 0) {
                $tr = "<tr " . $optionalRowTags . ">";
            } else {
                $tr = "<tr>";
            }
            if (strlen($optionalCellTags) > 0) {
                $td = "<td " . $optionalCellTags . ">";
            } else {
                $td = "<td>";
            }

            // Style information for alternating rows
            $secondStyle = false;
            if (strlen($optionalRowTags2) > 0) {
                $tr2 = "<tr " . $optionalRowTags2 . ">";
                $secondStyle = true;
            } else {
                $tr2 = "<tr>";
            }
            if (strlen($optionalCellTags2) > 0) {
                $td2 = "<td " . $optionalCellTags2 . ">";
                $secondStyle = true;
            } else {
                $td2 = "<td>";
            }

            // Show the header row (ONLY WORKS WITH ASSOCIATIVE ARRAYS)
            if ($showAssocArrayColumnTitles) {
                $html .= $tabRow . $trHeading . $nl;
                foreach ($array[0] as $key => $value) {
                    $html .= $tabCell . $tdHeading . htmlspecialchars($key) . "</td>" . $nl;
                }
                $html .= $tabRow . "</tr>" . $nl;
            }

            //Show the records
            $rowNumber = 0;
            foreach ($array as $row) {
                $rowNumber++;
                if ($secondStyle && ($rowNumber % 2 == 0)) {
                    $html .= $tabRow . $tr2 . $nl;
                    foreach ($row as $column) {
                        if (is_null($column) || strlen($column) == 0) {
                            $html .= $tabCell . $td . "&nbsp;</td>" . $nl;
                        } else {
                            $html .= $tabCell . $td2 . htmlspecialchars($column) . "</td>" . $nl;
                        }
                    }
                    $html .= $tabRow . "</tr>" . $nl;
                } else {
                    $html .= $tabRow . $tr . $nl;
                    foreach ($row as $column) {
                        if (is_null($column) || strlen($column) == 0) {
                            $html .= $tabCell . $td . "&nbsp;</td>" . $nl;
                        } else {
                            $html .= $tabCell . $td . htmlspecialchars($column) . "</td>" . $nl;
                        }
                    }
                    $html .= $tabRow . "</tr>" . $nl;
                }
            }

            return $html;
        } else { // No records
            return false;
        }
    }

    /**
     * Returns the checked tag for checkboxes and radio buttons
     * based on a string (i.e. value from a post) or boolean.
     * This is helpful when reposting forms that fail validation.
     * (STATIC method - use Template::getHTMLChecked)
     *
     * @param string/boolean $isChecked true, "on", "selected",
     * "checked", "yes", "y", "true", or "t" (not case-sensitive)
     * @return string Returns checked="checked" if TRUE, blank if FALSE
     */
    public static function getHTMLChecked($isChecked)
    {
        if (gettype($isChecked) == "boolean") {
            if ($isChecked == true) {
                return ' checked="checked"';
            } else {
                return '';
            }
        } elseif (is_numeric($isChecked)) {
            if ($isChecked > 0) {
                return ' checked="checked"';
            } else {
                return '';
            }
        } else {
            $cleaned = strtoupper(trim($isChecked));

            if ($cleaned == "ON") {
                return ' checked="checked"';
            } elseif ($cleaned == "SELECTED" || $cleaned == "CHECKED") {
                return ' checked="checked"';
            } elseif ($cleaned == "YES" || $cleaned == "Y") {
                return ' checked="checked"';
            } elseif ($cleaned == "TRUE" || $cleaned == "T") {
                return ' checked="checked"';
            } else {
                return '';
            }
        }
    }
}
?>