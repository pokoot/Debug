<?php

namespace Goldfinger;

use Goldfinger\Timer;

/**
 * A php debugging tool that supports stack and array traces with profiling information.
 *
 * @package
 * @version $id$
 * @author Harold Kim
 * @license http://pokoot.com/license.txt
 */
class Debug
{

    /**
     * Instance of Timer class.
     *
     * @var mixed
     * @access private
     */
    private $Timer;

    /**
     * Log information contents.
     *
     * @var mixed
     * @access public
     */
    public $contents;

    /**
     * File constructor
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        $this->Timer = new Timer();
        $this->Timer->start();
    }

    /**
     * Shows the error log message with a light red background.
     *
     * @access public
     * @param  mixed $content
     * @return void
     */
    public function error($content)
    {
        $this->log($content, "ERROR", debug_backtrace());
    }

    /**
     * Shows a warning log message with a light yellow background.
     *
     * @access public
     * @param  mixed $content
     * @return void
     */
    public function warn($content)
    {
        $this->log($content, 'WARN', debug_backtrace());
    }

    /**
     * Shows a header log information with a default light green background.
     *
     * @access public
     * @param  mixed  $content
     * @param  string $color
     * @return void
     */
    public function header($content, $color = '#C7E8C8')
    {
        $content = trim($content);

        $html = "
            <div class='entry' style='background-color:$color'>
                <div class='info'></div>
                <div class='content'><b>$content</b></div>
            </div>";
        $this->contents .= $html;
    }

    /**
     * Shows the log in html pre formatted.
     *
     * @access public
     * @param  mixed $content
     * @return void
     */
    public function pre($content)
    {
        $this->log("<pre>$content</pre>", 'QUERY', debug_backtrace());
    }

    /**
     * Shows the log information.
     *
     * @access public
     * @param  mixed  $content
     * @param  string $type
     * @param  string $backtrace
     * @return void
     */
    public function log($content, $type = '', $backtrace = '')
    {
        if (!$backtrace) {
            $backtrace = debug_backtrace();
        }

        $backtrace = $backtrace[0];
        $file = basename($backtrace['file']);
        $line = $backtrace['line'];

        switch ($type) {
            case "WARN":
                $display = "warn";
                break;
            case "ERROR":
                $display = "error";
                break;
            case "QUERY":
            default:
                $display = "";
                break;
        }

        $content = trim($content);
        $time = $this->Timer->getTime();

        $html = "
            <div class='entry $display'>
                <div class='line'>{$line}</div>
                <div class='content'>$content</div>
                <div class='info'>
                    <span class='file'>$file</span>
                    <span class='time'>: {$time}</span>
                </div>
            </div>";

        $this->contents .= $html;
    }

    /**
     * Dumps an array or an object with proper indentations.
     *
     * @access public
     * @param  mixed $value
     * @param  int   $level
     * @return void
     */
    public function dump($value, $level = 0)
    {
        if ($level == 0) {

            $backtrace  = debug_backtrace();
            $backtrace  = $backtrace[0];
            $file       = basename($backtrace['file']);
            $line       = $backtrace['line'];
            $time       = $this->Timer->getTime();

            $html = "<div class='entry'>
                        <div class='info'>
                            <span class='file'>$file</span>
                            <span class='time'>: {$time}</span>
                        </div>";

            $this->contents .= $html;
            $this->contents .= "<div class='line'>$line</div>
                                <div class='content'><pre>";
        }

        switch (strtoupper(gettype($value))) {
            case "STRING":
                $value = $value;
                break;
            case "BOOLEAN":
                $value = $value ? true : false;
                break;
            case "OBJECT":
                $props = get_class_vars(get_class($value));
                $this->contents .= 'Object('.count($props).') <u>'.get_class($value).'</u>';
                foreach ($props as $key => $val) {
                    $this->contents .= "\n". str_repeat("&nbsp;", ($level+1)*4) . "[" . $key . "]" . ' => ';
                    $this->dump($value->$key, $level+1);
                }
                $value= '';
                break;
            case "ARRAY":
                $this->contents .= ucfirst(gettype($value)) . '('.count($value).')';
                foreach ($value as $key => $val) {
                    $this->contents .= "\n" . str_repeat("&nbsp;", ($level+1)*4) . "[" . $key . "]" . ' => ';
                    $this->dump($val, $level+1);
                }
                $value= '';
                break;
        }
        $this->contents .= "$value";

        if ($level==0) {
            $this->contents .= '</pre></div></div>';
        }

    }

    /**
     * Renders the look and feel of the Debugging class.
     *
     * @access private
     * @return string
     */
    private function css()
    {
        $css = "
            <style>
                .debug {
                    background-color:#F5f5f5;
                    font-size:14px;
                    font-family: consolas, monospace;
                    color:#2B2B2B;
                    padding:2px;
                }
                .debug .error {
                    background-color:#FFEBE8;
                }
                .debug .warn {
                    background-color:#FFFFCC;
                }
                .entry {
                    min-height: 20px;
                    overflow:hidden;
                    padding: 4px;
                    cursor:pointer;
                }
                .entry:hover {
                    background-color: #FCF5BE;
                }
                .entry .info {
                    float:right;
                    color:#2B2B2B;
                    font-size:12px;
                    font-family: consolas;
                    background-color:#D6D6D6;
                    margin:2px;
                    padding:2px 6px;
                }
                .entry .info .file {
                }
                .entry .info .time {
                }
                .entry .line {
                    width: 50px;
                    background-color:pink;
                    float:left;
                    text-align:center;
                    margin-top: 2px;
                    background-color:#D6D6D6;
                }
                .entry .content {
                    margin-top: 2px;
                    float:left;
                    margin-left: 6px;
                }
                .entry .content pre {
                    margin: 0px;
                }
            </style>";

        return $css;
    }

    /**
     * Shows all the debugging messages.
     *
     * @access public
     * @return void
     */
    public function show()
    {
        $contents = $this->contents;
        $css = $this->css();

        ob_start(function ($buffer) use ($contents, $css) {

            $html = "$css<div class='debug'>$contents</div>";

            return str_replace("{{DEBUG}}", $html, $buffer);
        });

        print "{{DEBUG}}";

        ob_end_flush();
    }
}
