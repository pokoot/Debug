<?php

namespace Goldfinger;

class Debug
{

    public $contents;

    public function __construct(){
    }


    public function error($content)
    {
        $this->log($content, "ERROR", debug_backtrace());
    }

    public function warn($content)
    {
        $this->log($content, 'WARN', debug_backtrace());
    }

    public function log($content, $type = '', $backtrace = '' )
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

        $html = "
            <div class='entry $display'>
                <div class='header'>
                    <span class='file'>$file</span>
                    <span class='line'>Line:$line</span>
                    <span class='time'>0.0345S</span>
                </div>
                <div class='info'>$content</div>
            </div>";

        $this->contents .= $html;
    }

    public function header($content, $color = '#c7e8c8')
    {
        $content = trim($content);

        $html = "
            <div class='entry' style='background-color:$color'>
                <div class='header'></div>
                <div class='info'><b>$content</b></div>
            </div>";
        $this->contents .= $html;
    }


    public function query($content)
    {
        $this->log("<pre>$content</pre>", 'QUERY', debug_backtrace());
    }









    public function dump($value, $level=0) {

        $type = gettype($value);

        if ($level == 0) {

            $backtrace = debug_backtrace();
            foreach($backtrace AS $entry) {
                if ($entry['function'] == __FUNCTION__) {

                    $file = basename( $entry['file'] );
                    $line = $entry['line'];

                    $html = "
                            <div class='entry'>
                                <div class='header'>
                                    <span class='file'>$file</span>
                                    <span class='line'>Line:$line</span>
                                </div>";


                    $this->contents .= $html;
                }
            }

            $this->contents .= "<div class='info'><pre>";
        }

        print "<Br/> type = $type ";

        if( $type == 'string' ){
            $value = $value;
        }else if( $type=='boolean'){
            $value = $value ? 'true' : 'false';
        }else if( $type=='object'){
            $props = get_class_vars(get_class($value));
            $this->contents .= 'Object('.count($props).') <u>'.get_class($value).'</u>';
            foreach($props as $key => $val ){

                $this->contents .= "\n" . str_repeat("&nbsp;", ($level+1) * 4 ) . "[" . $key . "]" . ' => ';
                $this->dump( $value->$key , $level+1 );
            }
            $value= '';
        }else if( $type == 'array' ){
            $this->contents .= ucfirst( $type ) . '('.count($value).')';
            foreach($value as $key => $val){

                $this->contents .= "\n" . str_repeat( "&nbsp;" , ( $level+1 ) * 4 ) . "[" . $key . "]" . ' => ';
                $this->dump( $val , $level+1 );
            }
            $value= '';
        }


        $this->contents .= "$value";


        if( $level==0 ){
            $this->contents .= '</pre></div></div>';
        }

    }



















    private function css(){

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
                    padding: 6px;
                    cursor:pointer;
                }
                .entry:hover {
                    background-color: #FCF5BE;
                }
                .entry .header {
                    float:right;
                }
                .entry .header .time {
                    display: inline-block;
                    font-weight:bold;
                    border-radius:5px;
                    padding: 2px 6px 3px 6px;
                    background-color: #d4d4d4;
                    color:#2B2B2B;
                }
                .entry .header .line {
                    display: inline-block;
                    font-weight:bold;
                    border-radius:5px;
                    padding: 2px 6px 3px 6px;
                    background-color: #d4d4d4;
                    color:#2B2B2B;
                }
                .entry .header .file {
                    display: inline-block;
                    font-weight:bold;
                    border-radius:4px;
                    padding: 2px 6px 3px 6px;
                    background-color: #d4d4d4;
                    color:#2B2B2B;
                }
                .entry .info {
                    margin-top: 2px;
                    float:left;
                }
            </style>";

        return $css;
    }



    public function show(){

        $contents = $this->contents;
        $css = $this->css();

        ob_start(function($buffer) use ($contents, $css) {

            $html = "$css<div class='debug'>$contents</div>";

            return str_replace("{{DEBUG}}", $html, $buffer);
        });

        print "{{DEBUG}}";

        ob_end_flush();

    }

}
