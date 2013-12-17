<?php

namespace Goldfinger;

class Debug
{

    public $contents;

    public function __construct(){
    }


    public function error($content, $type = 'ERROR')
    {
        $this->log($content, $type);
    }

    public function warn($content, $type = 'WARN')
    {
        $this->log($content, $type);
    }

    public function log($content, $type = "")
    {

        switch($type){
            case "WARN":
                $display = "warn";
                break;
            case "ERROR":
                $display = "error";
                break;
            default:
                $display = "";
                break;
        }

        $content = trim($content);

        $html = "
            <div class='entry $display'>
                <div class='header'>
                    <span class='time'>0.0345S</span>
                    <span class='file'>/test.php/index.file.php</span>
                    <span class='line'>123</span>
                </div>
                <div class='info'>$content</div>
            </div>";

        $this->contents .= $html;
    }

    public function header($content)
    {

    }


    public function query($content)
    {

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

                .entry .header .time {
                    color: #4288CE;
                    font-weight:bold;
                }
                .entry .header .time:before {
                    content: '[';
                }
                .entry .header .time:after {
                    content: ']';
                }

                .entry .header .line {
                    color: #4288CE;
                    font-weight:bold;
                }
                .entry .header .line:before {
                    content: ' Line:'
                }
                .entry .header .file {
                    word-wrap: break-word;
                    font-style:italic;
                }

                .entry .info {
                    margin-top: 2px;
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
