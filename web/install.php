<?php

if (!isset($_SERVER['HTTP_HOST'])) {
    exit('This script cannot be run from the CLI. Run it from a browser.');
}

use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\Loader\YamlFileLoader;
use Symfony\Component\Yaml\Yaml;

require_once __DIR__ . '/../app/OroRequirements.php';
require_once __DIR__ . '/../vendor/autoload.php';

// check for installed system
$paramFile = __DIR__ . '/../app/config/parameters.yml';

if (file_exists($paramFile)) {
    $data = Yaml::parse($paramFile);

    if (is_array($data)
        && isset($data['parameters'])
        && isset($data['parameters']['installed'])
        && false != $data['parameters']['installed']
    ) {
        header('Location: /');

        exit;
    }
}

/**
 * @todo Identify correct locale (headers?)
 */
$locale           = 'en';
$collection       = new OroRequirements();
$translator       = new Translator($locale);
$majorProblems    = $collection->getFailedRequirements();
$minorProblems    = $collection->getFailedRecommendations();

$translator->addLoader('yml', new YamlFileLoader());
$translator->addResource('yml', __DIR__ . '/../app/Resources/translations/install.' . $locale . '.yml', $locale);

function iterateRequirements(array $collection)
{
    foreach ($collection as $requirement) :
?>
    <tr>
        <td class="dark">
            <?php if ($requirement->isFulfilled()) : ?>
            <span class="icon-yes">
            <?php elseif (!$requirement->isOptional()) : ?>
            <span class="icon-no">
            <?php else : ?>
            <span class="icon-warning">
            <?php endif; ?>
            <?php echo $requirement->getTestMessage(); ?>
            </span>
        </td>
        <td><?php echo $requirement->isFulfilled() ? 'OK' : $requirement->getHelpHtml(); ?></td>
    </tr>
<?php
    endforeach;
}
?>
<!doctype html>
<!--[if IE 7 ]><html class="no-js ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="no-js ie ie8" lang="en"> <![endif]-->
<!--[if IE 9 ]><html class="no-js ie ie9" lang="en"> <![endif]-->
<!--[if (gte IE 10)|!(IE)]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title><?php echo $translator->trans('title'); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style type="text/css">
        html,body,div,span,object,iframe,h1,h2,h3,h4,h5,h6,p,blockquote,pre,abbr,address,cite,code,del,dfn,em,img,ins,kbd,q,samp,small,strong,sub,sup,var,b,i,dl,dt,dd,ol,ul,li,fieldset,form,label,legend,table,caption,tbody,tfoot,thead,tr,th,td{margin:0;padding:0;border:0;outline:0;font-size:100%;vertical-align:baseline;background:transparent}a{text-decoration:none}blockquote,q{quotes:none}blockquote:before,blockquote:after,q:before,q:after{content:'';content:none}a{margin:0;padding:0;font-size:100%;vertical-align:baseline;background:transparent}ins{background-color:#ff9;color:#000;text-decoration:none}del{text-decoration:line-through}table{border-collapse:collapse;border-spacing:0}hr{display:block;height:1px;border:0;border-top:1px solid #ccc;margin:1em 0;padding:0}input,select{vertical-align:middle}html{overflow-y:scroll}a:hover,a:active,input,input:active{outline:0}ul,ol{list-style:none}textarea{overflow:auto}input[type=radio]{vertical-align:text-bottom}input[type=checkbox]{vertical-align:bottom}.ie7 input[type=checkbox]{vertical-align:baseline}.ie6 input{vertical-align:text-bottom}label,input[type=button],input[type=submit],button{cursor:pointer}.ie6 legend,.ie7 legend{margin-left:-7px}button,input,select,textarea{margin:0}::-moz-selection{background:#ff5e99;color:#fff;text-shadow:none}::selection{background:#ff5e99;color:#fff;text-shadow:none}a:link{-webkit-tap-highlight-color:#ff5e99}button{width:auto;overflow:visible}.ie7 img{-ms-interpolation-mode:bicubic}.clearfix:before,.clearfix:after,.f_row:before,.f_row:after{content:"\0020";display:block;height:0;visibility:hidden}.clearfix:after,.f_row:after{clear:both}.ie6 .clearfix,.ie7 .clearfix,.ie6 .f_row,.ie7 .f_row{zoom:1}.none_submit{background:transparent;border:0;width:0;height:0;position:absolute;filter:alpha(opacity=0);-moz-opacity:0;-khtml-opacity:0;opacity:0}body{margin:0;color:#666;min-width:1024px;background:#fff;font:13px/18px 'Helvetica Neue',Arial,sans-serif}form,fieldset{margin:0;padding:0;border-style:none}img{border-style:none;vertical-align:top}input,select,textarea{vertical-align:middle;font:100% arial,helvetica,sans-serif}a{text-decoration:none}a:hover{text-decoration:underline}.header,header{padding:14px 15px;background-color:#3b4651;background:-webkit-gradient(linear,0 0,0 100%,from(#3b4651),to(#323b45));background:-webkit-linear-gradient(top,#3b4651,#323b45);background:-moz-linear-gradient(top,#3b4651,#323b45);background:-ms-linear-gradient(top,#3b4651,#323b45);background:-o-linear-gradient(top,#3b4651,#323b45)}.logo{font:16px/16px 'Helvetica Neue',Arial,sans-serif;text-transform:uppercase;color:#fff;float:left}.header:after,header:after{content:"";clear:both;display:block}.helper{text-indent:-9999px;font-size:0;line-height:0;background-image:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA4AAAAOCAYAAAAfSC3RAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoV2luZG93cykiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6NkEwQUMwN0QyMTQ0MTFFMzhCQzREOUQ2MzZGMDdCRDMiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6NkEwQUMwN0UyMTQ0MTFFMzhCQzREOUQ2MzZGMDdCRDMiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDo2QTBBQzA3QjIxNDQxMUUzOEJDNEQ5RDYzNkYwN0JEMyIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDo2QTBBQzA3QzIxNDQxMUUzOEJDNEQ5RDYzNkYwN0JEMyIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/Pgyh9WAAAAHJSURBVHjaYvz//z8DMgiMSv5vZmzIICUpzvDz5y+Gx0+fMTTXlDIyoAOQRhjevmvf/z9///5HB5+/fPnfO2nmf2S1cMbRE6dRFD949OT/nXsP/v/79w8uNnH63P8oGpE1Xbh89b9PaPx/c0ef/xZOvv8DIpP/37l7Hy7fP3U2WDPzf1ae/3GRIWBnP3v+kiGjoJzhw8dPDMJCAkARRiD7A8P+w8cZIkIDGJgYGRmMDfQYfMPiG5h8PF3g/hUXF2UI8fdm8HBxYNi8aiHDkjmTwS569/4Dw42bt8FqWFlZwPIsivJycI3MTEwMmSnxYPbHT58Y+qfOAtvKBlQsKSEOV6eoIMfAwsvDjRHSf//+ZUjMLAI6/QWDkKAgQ31lIZAWgMvzAPUwvXj1GkPjles3wZqYmJgZZk/uZjA3MUKRf/HiFQPL2fOXGORlZVAkpCUlGIpy0hhYWFkZpKUkMAw+e+EyA0NEYtb/P3/+oMQhKHrMHLyB2AsjMTx68hQcHUzL501lrG7qBPsLBvh4ecA2KcrLotj0EugtWWkpRpQkV17XimEzMnjx8hX2JAfCSZlF//cdPPL/x48fcA3AOPy/dNU6FE0gDBBgAIzgdnstATR0AAAAAElFTkSuQmCC);width:14px;height:14px;float:right}.wrapper{margin: 0 auto;padding:27px 25px;background-color:#fff;width:990px;box-sizing: border-box; -moz-box-sizing: border-box; -webkit-box-sizing: border-box;}.wrapper>.content{border:2px solid #dededf;-webkit-border-radius:7px;border-radius:7px}.progress-bar{padding:25px;text-align:center;background-color:#f1f1f1;border-bottom:1px solid #dadada}.progress-bar ul{display:inline-block;background-image:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAAFCAIAAAAL5hHIAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoV2luZG93cykiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6NDM2RkFDMkIyMTM3MTFFMzlERjRCNjU3NjEzRTZEMzgiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6NDM2RkFDMkMyMTM3MTFFMzlERjRCNjU3NjEzRTZEMzgiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDo0MzZGQUMyOTIxMzcxMUUzOURGNEI2NTc2MTNFNkQzOCIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDo0MzZGQUMyQTIxMzcxMUUzOURGNEI2NTc2MTNFNkQzOCIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/Po99mJEAAAAgSURBVHjaYrhz5w4TIyMjw+PHjxmePn3K8OLFC4AAAwBe6gqq5jidAAAAAABJRU5ErkJggg==);background-repeat:repeat-x;background-position:0 10px;text-align:center}.progress-bar li{position:relative;display:inline-block;color:#b9b9b9;font-size:14px;text-align:center}.progress-bar li:first-child{position:relative;left:-25%}.progress-bar li:last-child{position:relative;right:-18%}.progress-bar li>span{display:block;clear:both}.progress-bar li.active{color:#2f2f2f;font-weight:700}.progress-bar .step{display:inline-block;background-color:#fff;font-size:14px;font-weight:700;color:#dedede;-webkit-border-radius:12px;border-radius:12px;width:20px;line-height:20px;height:20px;clear:both;margin:2px;text-align:center}.progress-bar .active .step{margin:0;border:2px solid #2f2f2f;color:#2f2f2f}.page-title{color:#444;font-size:17px;line-height:20px;font-weight:700;padding:15px 30px;background-color:#fafafa}.well{padding:30px}.table,table.table{border:1px solid #e1e1e1;width:100%;-webkit-border-radius:4px;border-radius:4px}table.table tr,table.table td,table.table th{border:1px solid #e1e1e1;border-collapse:collapse;line-height:22px;text-align:left}table.table td,table.table th{padding:10px 15px}table.table th{background-color:#f6f6f6;background:-webkit-gradient(linear,0 0,0 100%,from(#f6f6f6),to(#f4f4f4));background:-webkit-linear-gradient(top,#f6f6f6,#f4f4f4);background:-moz-linear-gradient(top,#f6f6f6,#f4f4f4);background:-ms-linear-gradient(top,#f6f6f6,#f4f4f4);background:-o-linear-gradient(top,#f6f6f6,#f4f4f4);text-transform:uppercase}table.table .dark{background-color:#f9f9f9}table.table{margin-bottom:30px}.well>:last-child{margin-bottom:0}.button-set{background-color:#f7f7f7;padding:35px 30px 40px;overflow:hidden;border-top:1px solid #eaeaea}.pull-right{float:right}.pull-right .button{margin-right:10px}.next span:after{content:'';float:right;width:8px;height:13px;background-image:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAgAAAANCAYAAACUwi84AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoV2luZG93cykiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6QjJFM0Y4N0MyMTQ0MTFFM0I2OUE4NzIyOUY3NzVCOEYiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6QjJFM0Y4N0QyMTQ0MTFFM0I2OUE4NzIyOUY3NzVCOEYiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDpCMkUzRjg3QTIxNDQxMUUzQjY5QTg3MjI5Rjc3NUI4RiIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDpCMkUzRjg3QjIxNDQxMUUzQjY5QTg3MjI5Rjc3NUI4RiIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PtAX/MIAAACvSURBVHjaYvj//z/Drcdf/2888vo/iI2OGW4+/Pz/379/YLzz1BsMRQzrd1+GK8CmCEys2nwMRdGOkwjr4CrRFa0/9Pw/igKIoqNwBd9+/P4/cfUDVAedvfoMoeD7T1QTLt7+gJD89uN/+5TNCAUXbr1HkWybsgnhyBNX3qJJbkT15qHTd+GSrZM3YgQUy6Kt1xg+fnjHcOTsPYaO8jBGBnQAUpXatA1rPIAwQIABAPi+SJHajeXNAAAAAElFTkSuQmCC);margin:9px 0 0 15px}.button,.back{-moz-transition:all 10ms ease;-webkit-transition:all 10ms ease;-o-transition:all 10ms ease;transition:all 10ms ease;background-color:#f8f8f8;background:-webkit-gradient(linear,0 0,0 100%,from(#f8f8f8),to(#f1f1f1));background:-webkit-linear-gradient(top,#f8f8f8,#f1f1f1);background:-moz-linear-gradient(top,#f8f8f8,#f1f1f1);background:-ms-linear-gradient(top,#f8f8f8,#f1f1f1);background:-o-linear-gradient(top,#f8f8f8,#f1f1f1t);border:1px solid #d3d3d3;font-weight:700;font:700 15px/30px 'Helvetica Neue',Arial,sans-serif;height:34px;padding:0 12px;color:#777;-webkit-border-radius:4px;border-radius:4px;float:left;cursor:pointer}.button span{min-width:75px;display:inline-block;text-align:center}a.button{float:left;height:32px;font:700 15px/32px 'Helvetica Neue',Arial,sans-serif;cursor:pointer}a.button:hover{text-decoration:none}.button:hover,.button:focus{background-color:#f1f1f1;background:-webkit-gradient(linear,0 0,0 100%,from(#f1f1f1),to(#f8f8f8));background:-webkit-linear-gradient(top,#f1f1f1,#f8f8f8);background:-moz-linear-gradient(top,#f1f1f1,#f8f8f8);background:-ms-linear-gradient(top,#f1f1f1,#f8f8f8);background:-o-linear-gradient(top,#f1f1f1,#f8f8f8)}.primary{background-color:#6d8fcd;background:-webkit-gradient(linear,0 0,0 100%,from(#88abe9),to(#6d8fcd));background:-webkit-linear-gradient(top,#88abe9,#6d8fcd);background:-moz-linear-gradient(top,#88abe9,#6d8fcd);background:-ms-linear-gradient(top,#88abe9,#6d8fcd);background:-o-linear-gradient(top,#88abe9,#6d8fcd);border-color:#768bb0;color:#fff}.primary:hover,.primary:focus{background-color:#88abe9;background:-webkit-gradient(linear,0 0,0 100%,from(#6d8fcd),to(#88abe9));background:-webkit-linear-gradient(top,#6d8fcd,#88abe9);background:-moz-linear-gradient(top,#6d8fcd,#88abe9);background:-ms-linear-gradient(top,#6d8fcd,#88abe9);background:-o-linear-gradient(top,#6d8fcd,#6d8fcd)}.icon-yes{display:inline-block}.button.disabled{background:#ededed;border:1px solid #dcdcda;cursor:no-drop;color:#fff}.button.disabled span:after,.button.disabled:before,.button.disabled[class^=icon-]:before{opacity:.2}.icon-reset:before,.icon-settings:before,.icon-warning:before,.icon-no:before,.icon-yes:before{float:left;text-indent:-9999px;font-size:0;line-height:0;content:'';width:21px;height:21px;margin:0 10px 0 0}.icon-yes:before{background-image:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABUAAAAVCAYAAACpF6WWAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoV2luZG93cykiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6RTA0QUQ4NzIyMTQ0MTFFM0FCMDBGMDhFMzdCMEUxMDYiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6RTA0QUQ4NzMyMTQ0MTFFM0FCMDBGMDhFMzdCMEUxMDYiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDpFMDRBRDg3MDIxNDQxMUUzQUIwMEYwOEUzN0IwRTEwNiIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDpFMDRBRDg3MTIxNDQxMUUzQUIwMEYwOEUzN0IwRTEwNiIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/Pset+CgAAAEySURBVHjatJXBDYIwFIZLw8ErK7CCLmBSR3AALzKCjCAjwMUBHEESF5AVWKFXb/ja/E2aWmgBfckfSAsff/veK8kwDMyN/W2jLhlJkLaQCknqSC2u3khcKAEV7Ey6ADwWPanAB8ahBFSOastZTDSAf0MBfATcRYE1FEt+kXK2PCpSqW44Bq4rgQw5EBpKLnMkZsmSS2dMc1LSceUeSiSXgZVxY9mJdka2G4BNCO4pH/XCwS2TsfKBS7ticu4MVM/Tu5lw5AO6+chTWDdggfKSFohhNTFAZvbU7mFfAzQzgCo67klKqLPqQAm2Cnr3TIyBQ8BeO6XE9NbeTYHriCapYnu/Q+JEANiiFH92SnUASvtAYbQNZqJf0LIHu6u4PQvwDgeFDMDMyV+4zyb/+Ed9BBgAWYZxCzjqKh4AAAAASUVORK5CYII=)}.icon-no:before{background-image:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABUAAAAVCAYAAACpF6WWAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoV2luZG93cykiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6MDdEQzNBQjMyMTQ1MTFFM0I4QkVEODI4RTc0MjIzMDAiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6MDdEQzNBQjQyMTQ1MTFFM0I4QkVEODI4RTc0MjIzMDAiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDowN0RDM0FCMTIxNDUxMUUzQjhCRUQ4MjhFNzQyMjMwMCIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDowN0RDM0FCMjIxNDUxMUUzQjhCRUQ4MjhFNzQyMjMwMCIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PjxJ9dMAAAFLSURBVHjatFWxbcNADHw9XLnTCqrS2yNYIyQjSCPYI8gjSCM4I+RHiPtUv4I7twppkAZNkK9AQA4gHiDME8m7f1fzPAeNn+0WjxriALGjQNwgrhCJThMbgxDJOogjEWu805khevrAC6IixI6+IAaHUKKh345up4JwiUyjo7N/6ZRGvqwglMSDHn+gcSRQiLNB4OWPJGzYQJeNGEEWtqR2Fnvz8rLjFIWaEicqREy0L0nI+WQ4o47csoJWHwn2gpC7smoPURhbYskJnWUltlosFO6kosqfY8kKUY2kxToZ+Sw96ZFeHcK28MGpQJyjdXeV+rzDb0M8q/Zhqc8F9VkULZ6lPnLdKnz64AKMzgVIdFP+kg+0ssSkNY3XhPU4s7CPu/92v+P+PgrCLGGSTnm+p0DMiucVhL37SBPx3lA/OH5tLWtV//Ef9SvAANMybFUWB7c8AAAAAElFTkSuQmCC)}.icon-warning:before{background-image:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABUAAAAUCAYAAABiS3YzAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoV2luZG93cykiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6MjEyN0Y3NTYyMTQ1MTFFM0IyRDFFQTkxQkVGRjU4RTIiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6MjEyN0Y3NTcyMTQ1MTFFM0IyRDFFQTkxQkVGRjU4RTIiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDoyMTI3Rjc1NDIxNDUxMUUzQjJEMUVBOTFCRUZGNThFMiIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDoyMTI3Rjc1NTIxNDUxMUUzQjJEMUVBOTFCRUZGNThFMiIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PqXq6NYAAAGMSURBVHjarJMxSAJRGMf/p5IV1Ba4tBQFNTY1tjg0inuNFbRE0lDQEkR7Qy0NIdHW0NCQEdJWmFdLpwRaImoINlwkivr1nXfVXdfZM/3D49773sfvfffe93cREYSGqpJorgsiio4QTgf4e00i6X9Dn/YIubQ+zy3zukYdQguExKppHQMSZx1WquwCpbI1Vlrk+Bv9D1pXCMntXzbyQDLcshaP4879FvCuHTsN+GaNYIZv5IDjfCXxAGHKJ4lXWpcJqWN97p0DZjaNwTCvFuQrSYXb/P3bEFA15uWsaeO1yWuqugbcPJMYVI1wlZdC7YvUjmCl8gLQEGOisQ9cydQamj8iZNPOkHrNHsuGtIawgCXNq1+K9BOKP/oSffw44/rxjQJQebGDh84Bv1+yV6rZ0QYUVHHdYt9vqMWOZq0AwTsgwCN44pBjta8OVTbIZsd2ZbKvx9mOn+L7ivuBHq03L1rkGfadWOKHkucJD4fojvhRJzP8++5BdFXuXqOlHqOESheA3lFgbFj6EGAA8tzO3pT9eVsAAAAASUVORK5CYII=)}.icon-settings:before{background-image:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAASCAYAAABSO15qAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoV2luZG93cykiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6QjFGNzk3QzYyMTQxMTFFM0FBOEJFMTA2Mjg0QTQ3RUMiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6QjFGNzk3QzcyMTQxMTFFM0FBOEJFMTA2Mjg0QTQ3RUMiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDpCMUY3OTdDNDIxNDExMUUzQUE4QkUxMDYyODRBNDdFQyIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDpCMUY3OTdDNTIxNDExMUUzQUE4QkUxMDYyODRBNDdFQyIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/Pl1QYWEAAAGfSURBVHjaYmHAAf7//68EpJSg3LOMjIzvGUgBP3786PsPBb9//67CpY4JyUZBIF4FxKEg/p8/f6yQDLODqkmDqhFEd67gv3//zv5HgDP/McFdGAOqVhDugu/fv5sD/WiEZKYxFtfCwoMBaIDaq1evxOAGcHFxXTh//vxyYsPnypUrG8TFxZ/CDQA6RxoIviErAhkIdFUUCKMbLikp+ROohxXm/93onr13795eoJQWkh6tZ8+encASLquYPn36JIjFldegGM7/9u3bOXRFnz9/VmQCxvEXdAlBQUFxdDGgn5XQxX79+vUVRFv7+fmlXL9+fROa88qRorkcWQKktrS0NB+kF6aG7dGjRxP+EwlAaoF6eFDS/X8SwN+/fz9C8wokGnft2vULGJj3YQYis7GJff369Q1ID7I8W2RkZDAoqrZt2wZynvubN28uw2wEsUFihw8fng1SA1IL0oNuCRs0UORAnIcPHy6DGXDjxo1ZUDUqUDVwzSzIsQLER2GcHTt2LOXh4bkPTZWbocJ3oBgOGPEkeR6kkH4HtQADAAQYAGT8pPdjinMXAAAAAElFTkSuQmCC);width:16px;height:18px;margin:6px 0 0}.icon-reset:before{background-image:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABMAAAATCAYAAAByUDbMAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoV2luZG93cykiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6N0UxRkIwRUQyMTQyMTFFM0ExN0JDQTJCQjJFRUUwMDUiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6N0UxRkIwRUUyMTQyMTFFM0ExN0JDQTJCQjJFRUUwMDUiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDo3RTFGQjBFQjIxNDIxMUUzQTE3QkNBMkJCMkVFRTAwNSIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDo3RTFGQjBFQzIxNDIxMUUzQTE3QkNBMkJCMkVFRTAwNSIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/Pv4YFXoAAAJCSURBVHjaYvz//z8DLnDryJb/l178YPj27RsDFxcXg4CCOYORriyDEDsDIzb1LBgiX67+X9VYztC65TrDl19YdHBKMZgE5v6vKw9j0OYBGvrz/P85mR0MT6OmMzAiu+zL6Qn/YxMnM1z6zkAYCJgzlE3JZfjdlcrQD9SgmrseYdjP8xP+B0dNZriO5BpOAR0GUwcVBiEw7xvDk1OHGc48w24TyDCIN99t/V+RgmQQ0Nbcng6GAkc5jLB5tH/C/4qSyQwnP2AayAQiTs3qYtgEkxTwYuhaPx+rQSAgZ2XPYCbJhtV1TAw/d/1fs/IJlMvH4FXbyBAsx47VIFBgT4mIZZh8/RcOw44fZtj7CcqTCWeIDRDCHehXDzBsvoEtzNgYhAT5UWOTUsDEQEVAVcNYHi6M+u/RdJIBI0jZNBkKV25hyNHDnnWAaeB/h30kw2xw3Kky5K7fwcD08f07Bqy5RsODwVodtyvebVjMAE8EMg4MVno4vMmmmcuweEUOgyGODM3wfMP/+uZtDLBEoB8VwmDGwMCI1bBfDw4y7Dz2CKs5oBwQ5VPMsA2WyGUSGYoTVHCUGiDw/RLD7BRHhiUCOv8RefMdw50DpxmufPiOEq65E0oZrKE+gBvGqVfIMLuMlWFyThc8333/cIXh0IYrOEuNyjnzGVIMEbmFSUbHksHEvhIcRpaW6YzLDm5iaI8yYZDixBHybGIMhlHtDJsOLkMxCARw54Cf7/4/vnyO4eSDD/CSlkNCj8HKVA1nSQsQYADAHNIIkFsyhwAAAABJRU5ErkJggg==);width:19px;height:19px;margin:6px 0 0}.row{margin-bottom:50px;display:inline-block;width:100%}.control-group:after,.row:after{content:"";clear:both;display:block}.row .box{float:left;width:45%;margin-right:5%}.control-group{width:100%;box-sizing:border-box;-moz-box-sizing:border-box;-webkit-box-sizing:border-box;padding:0 0 0 136px;margin:0 0 10px}.sub-title{font-size:15px;color:#444;margin-bottom:15px}.horizontal-form label{width:125px;text-align:right;margin:0 0 0 -136px;float:left;line-height:32px}.horizontal-form input[type=text],.horizontal-form input[type=number],.horizontal-form input[type=email],.horizontal-form input[type=password]{border:1px solid #e4e4e4;width:100%;box-sizing:border-box;-moz-box-sizing:border-box;-webkit-box-sizing:border-box;height:32px;line-height:32px;padding:0 5px;-webkit-border-radius:3px;border-radius:3px}.horizontal-form select{height:32px;line-height:23px;padding:5px 0;width:100%;box-sizing:border-box;-moz-box-sizing:border-box;-webkit-box-sizing:border-box;border:1px solid #e4e4e4;-webkit-border-radius:3px;border-radius:3px}.horizontal-form .checkbox{margin-top:8px;float:left}.horizontal-form label em{color:#c60000;font-size:15px;padding:0 5px}.validation-tooltip{color:red;padding:10px 0 0;display:block}.validation-error>ul{color:red;padding:10px 0;display:block}span.validation-faled:before{color:#C81717;content:"×";display:inline-block;font:bold 1.25em/1em Arial;margin-left:-14px;width:14px}span.validation-faled{clear:both;color:#C81717;display:block;line-height:1.1em;margin:5px 0 5px 14px}
        html,body{position:relative;min-height:100%}.start-box{position:absolute;top:0;left:0;width:100%;height:100%}.fade-box{position:absolute;top:0;left:0;width:100%;height:100%;background:#000;opacity:.2;z-index:55}.start-content{position:absolute;top:50%;left:0;width:100%;z-index:60}.start-content-holder{width:615px;margin:0 auto;background:#fff;-webkit-border-radius:5px;border-radius:5px;overflow:hidden;padding:55px 0 0;text-align:center;position:relative;top:-210px}.start-footer{background-color:#f7f7f7;overflow:hidden;text-align:center;padding:30px 0 50px;border-top:1px solid #eaeaea}.start-footer .button{display:inline-block;float:none}.start-content .center{text-align:center;margin-bottom:30px}.start-content .center img{vertical-align:top;text-align:center}.start-content-holder h2{font:700 31px/34px 'helvetica neue',Arial,sans-serif;margin:0 0 15px}.start-content-holder h3{font:16px/18px'helvetica neue',Arial,sans-serif;margin:0 0 45px}.progress-bar li{padding:0 30px}.progress-bar li:last-child{right:0;padding-right:0}.progress-bar li:first-child{left:0;padding-left:0}.progress-bar .fix-bg{display:none}.progress-bar span,.progress-bar .step{position:relative;z-index:50}.progress-bar li:first-child .fix-bg,.progress-bar li:last-child .fix-bg{display:block;position:absolute;top:0;height:100%;background:#f1f1f1;z-index:10}.progress-bar li:last-child .fix-bg{right:0}.progress-bar li:first-child .fix-bg{left:0}td.progress span{display:none}
    </style>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js" ></script>
    <script type="text/javascript">
        $(function() {
            $('.progress-bar li:last-child em.fix-bg').width($('.progress-bar li:last-child').width() / 2);
            $('.progress-bar li:first-child em.fix-bg').width($('.progress-bar li:first-child').width() / 2);

            var splash = $('div.start-box'),
                body = $('body'),
                winHeight = $(window).height();

            $('#begin-install').click(function() {
                splash.hide();
                body.css({ 'overflow': 'visible', 'height': 'auto' });
            });

            if ('localStorage' in window && window['localStorage'] !== null) {
                if (!localStorage.getItem('oroInstallSplash')) {
                    splash.show().height(winHeight);
                    body.css({ 'overflow': 'hidden', 'height': winHeight });

                    localStorage.setItem('oroInstallSplash', true);
                }
            }

            <?php if (!count($majorProblems)) : ?>
            // initiate application in background
            $.get('installer/flow/oro_installer/configure');
            <?php endif; ?>
        });
    </script>
</head>
<body>
    <header class="header">
        <h1 class="logo"><?php echo $translator->trans('title'); ?></h1>
    </header>
    <div class="wrapper">
        <div class="content">
            <div class="progress-bar">
                <ul>
                    <li class="active">
                        <em class="fix-bg">&nbsp;</em>
                        <strong class="step">1</strong>
                        <span><?php echo $translator->trans('process.step.check.header'); ?></span>
                    </li>
                    <li>
                        <em class="fix-bg">&nbsp;</em>
                        <strong class="step">2</strong>
                        <span><?php echo $translator->trans('process.step.configure'); ?></span>
                    </li>
                    <li>
                        <em class="fix-bg">&nbsp;</em>
                        <strong class="step">3</strong>
                        <span><?php echo $translator->trans('process.step.schema'); ?></span>
                    </li>
                    <li>
                        <em class="fix-bg">&nbsp;</em>
                        <strong class="step">4</strong>
                        <span><?php echo $translator->trans('process.step.setup'); ?></span>
                    </li>
                    <li>
                        <em class="fix-bg">&nbsp;</em>
                        <strong class="step">5</strong>
                        <span><?php echo $translator->trans('process.step.final'); ?></span>
                    </li>
                </ul>
            </div>

            <div class="page-title">
                <h2><?php echo $translator->trans('process.step.check.header'); ?></h2>
            </div>

            <div class="well">
                <?php if (count($majorProblems)) : ?>
                <div class="validation-error">
                    <ul>
                        <li><?php echo $translator->trans('process.step.check.invalid'); ?></li>
                        <?php if ($collection->hasPhpIniConfigIssue()): ?>
                        <li id="phpini">*
                            <?php
                                if ($collection->getPhpIniConfigPath()) :
                                    echo $translator->trans(
                                        'process.step.check.phpchanges',
                                        array(
                                            '%path%' => $collection->getPhpIniConfigPath()
                                        )
                                    );
                                else :
                                    echo $translator->trans('process.step.check.phpchanges');
                                endif;
                            ?>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
                <?php endif; ?>

                <table class="table">
                    <col width="75%" valign="top">
                    <col width="25%" valign="top">
                    <thead>
                        <tr>
                            <th><?php echo $translator->trans('process.step.check.table.mandatory'); ?></th>
                            <th><?php echo $translator->trans('process.step.check.table.check'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php iterateRequirements($collection->getMandatoryRequirements()); ?>
                    </tbody>
                </table>

                <table class="table">
                    <col width="75%" valign="top">
                    <col width="25%" valign="top">
                    <thead>
                        <tr>
                            <th><?php echo $translator->trans('process.step.check.table.php'); ?></th>
                            <th><?php echo $translator->trans('process.step.check.table.check'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php iterateRequirements($collection->getPhpIniRequirements()); ?>
                    </tbody>
                </table>

                <table class="table">
                    <col width="75%" valign="top">
                    <col width="25%" valign="top">
                    <thead>
                        <tr>
                            <th><?php echo $translator->trans('process.step.check.table.oro'); ?></th>
                            <th><?php echo $translator->trans('process.step.check.table.check'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php iterateRequirements($collection->getOroRequirements()); ?>
                    </tbody>
                </table>

                <table class="table">
                    <col width="75%" valign="top">
                    <col width="25%" valign="top">
                    <thead>
                        <tr>
                            <th><?php echo $translator->trans('process.step.check.table.optional'); ?></th>
                            <th><?php echo $translator->trans('process.step.check.table.check'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php iterateRequirements($collection->getRecommendations()); ?>
                    </tbody>
                </table>
            </div>
            <div class="button-set">
                <div class="pull-right">
                    <?php if (count($majorProblems) || count($minorProblems)): ?>
                    <a href="install.php" class="button icon-reset">
                        <span><?php echo $translator->trans('process.button.refresh'); ?></span>
                    </a>
                    <?php endif; ?>
                    <a href="<?php echo count($majorProblems) ? 'javascript: void(0);' : 'installer'; ?>" class="button next <?php echo count($majorProblems) ? 'disabled' : 'primary'; ?>">
                        <span><?php echo $translator->trans('process.button.next'); ?></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="start-box" style="display: none;">
        <div class="fade-box"></div>
        <div class="start-content">
            <div class="start-content-holder">
                <div class="center"><img alt="Insall" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAN4AAACiCAYAAAAuh3MNAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoV2luZG93cykiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6MkU1OEFDRjAyMjBCMTFFMzhBNEJGNzA2NzgyMzE3NDEiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6MkU1OEFDRjEyMjBCMTFFMzhBNEJGNzA2NzgyMzE3NDEiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDoyRTU4QUNFRTIyMEIxMUUzOEE0QkY3MDY3ODIzMTc0MSIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDoyRTU4QUNFRjIyMEIxMUUzOEE0QkY3MDY3ODIzMTc0MSIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PgCIKFYAAB7+SURBVHja7F1ZcxvXlT5oNBo7AVLWvpASJVm2HJO2k3icWURlahLnyfJUXuZJzC+I8gus/AIrv8D0vE2lpqKpSspJamJLqcTO4thUPJKsnVosWRRFYgca3Q3MPbdvgw0IIIFuAA0C53NdA6SI7kb3/e7Zz/Xd/+ohEACCoRAEg0EIBAKdfnSu4XVKjM1wQbwuibHIRqrdk1YqFSgWi6CWSlCtVukBeoR9e3Y7+pxvlInn8/k44cLhMEiS1C7JcMyKMdnlS0oLAl6wvW5KRlVVoVgogGEYxAQi3mATLhQKc8Lh+w2AkuuUINtbHl3uJUHA8zYpuTEBK0RAIt6AAQkXiUQ2IpxFtnk2Zgbs8tOCgAsbkRAJmM/nSAUl4nmPgByAWDy+kUo5L8aJLfKV7rJxTpDwGXUUSYc2YLFYIHYQ8bxRK6PRGHecNEFSkO1MD+y1fuJ9Ns6C6aSpg67rkM/lQDd0YgkRr09SLqBAnEm5JmplUpANR2KIvnJLAhaY7UfSj4jXcykXDke486QJzojJmRji+fC++J4pkn5EvL7AL/m5LSfLcuM/zQl7aHJE5kRa2IBnG22/fD4Pqloi1nhIPGmYbgI6UBLJZCPpkoJwH40Q6UBI9HfAjAfO2rWBWCwGkUiUWOMhhoZ4wWAIxhKJRnvulLB3To/wM8aQyOeNkg/V8Fgsvlkck0DEaw1cvXEVb5ByqGb9YshtuU7wjPRDT+/YWILI54Uf4t5XX23pL4CrdkOoYArMIPMMPd6Wtt8ZoX5zoNMlk0lTwN0B9u/ZM3oSrwnp5sSqTqTb2PZ7T2gEHGgTk+QjVdMp6ebBdKCQatkefiykXpLIR8Rr26ZrIN05sYoTOgM6nS4Q+Yh4mwK9lw2B8QWxehOcYaaRfKhNEIh4NfBE53rv5QKMdqigJ+RTFIXIR8QzgRkp8bExIl2fyIeqPGoXhN5Ahi3iQebBXvafuN5zRLqekm+OjVQsGgND13m4gTCCEg+dKbY0sHmy6XpOvgXrh3h8jJwto0g8tOtszhRcicl72Xu8ZZEPC4exnpEwQsTDldZm102BmZFC6A9OC+2C23tKQKE7MirEw5o6m5qDpKPgeH+B2gXP7YzGYqRyjgLxGlTMc0BpYF4BF7wkqpy4EBKGnHiRaNRu15EzxTtMioWPL4RNCowdwe/388UVh9lqMcIHqrT4u26dZ2DNqHsPBq86AeNHIlCOMaUlUjEHAm+j9NM0jVcydAoklBwwCdVJt24MZWCjXjxvuawOXAXF/r3OqhPkQQvj8X4pkYgVXjxLpBsYoNS7wMiTkplE0nStLXPBao1vRydzzs+IikNhx4hCzCSgqoI6gCTsaJ7fHTCJh+oMNpwVKuZHNN8HCj9j44wp9TIt/yjESIKLZ5tt8R3B6hta8njviAMOJd5AEQ+l3fj4uOU9o7q6wcQr+Gwy6TSTenqDhJM3axpsxxSsb+4yK8wK67mnxFhsh4DYuhAJuJWIN1AWbFBRLNLNE+kGWuWcQ4mmCalntlMMt2qnaMHagwJJ1km37kuwvoHLeWhoWWg2LDbLxLBtva5vjX0jBkriobRjq2VS3OhJmuMDi5NIhHTa5ACmlbWQcnNiEe1mXu1FMLNqniEhAlsX9lP6OVc17w8G8ZRAwMpSQYfKOzS3Bxo4+efQ44hhgSaB9XnxHHu5eFp9Q881EhA3bcnlcv0h3r4tTjxsta4oCoUPtpjUayLhFvqsraQFyc/Zf2k2cMr03PHilHgDEUDHFROLL4UdQKTbGjhre2+1U/SiaTDOl3ehoXWh2cZicCsrBkLihUIhbiALaUe23dbBK+L1/AA9t5/Ypd9moY+RlngiwHqKSLflsCDUzUF6bu+CrZ4Qs2Qa2oUMBDwnnt8vWXl58zSPtxxmBtQ0OC1Uz1obi1Dz/RFHl3iYViRu0Fs0jwldXhQuWOTDpHtc5AcFnudqyoqCuXsk7Qi9Ih/an3Pg8zHyxXpq720tiWdmqhPxCL3CCcvmw7k2KCqnp8STZR58nQJKDyP03ubji3s4MhjFvDJ42N8vwJ0q1TmaF4Q+gJc1SZJvKRRUoKSqoyvx/KY38xTNCUIfkLBUzkGQep42tJV8EgpckniEftp782zeLaC2pWneNer1VOIxY3cWKEWM0F+cxf953Z7eM+JhVjsASTtC34FZNvMiN3j0iMeMXHyZpXlA8ELqYfK0l53MvJN4Epd4UzQHCB5JvbnAKBLPZ1Ysn6A5QPAI83IHbQa7DS9Txqaq9PAJ3uEULv5ezUHvbDy/n9RMgpdIMBvPMx+DZ0quJElEvDaQylWg3EG4KRry8UFoC3PQRgvBoSIekGNlU/zm8zI8yVY6/tx3ng/AoZ1+uoFtEI9JvXNe7Hgrg1ddePG8VbLyWuFxynBEOsQXd3U4uN1H22q1sfj7rLlIEo+AWMkYjg3/TKkKlUrFSlIgtIZnVTESEW/wgKR56lDaWfh6TaMbOcCQ6BYMHrAX5NO8u2OUdVIz24GmaZ54Nj1TNdGgDXgYwBx04mV5uZhz2yPL1E08Dtl5myI5WhJvBBwrZb0Kj9Odq4zdUBPX8tBxF2XrevF1VFR6r9D3zBU0+HFXGWy5NsyP9/ZjAz69pYGqA2yPS/DPLwTajq9VcT10uTCpjLsVlHht/v2DpwZ8fM26Xh+8fiQA47HhtkR0wxj+zBVUeSKMcMlk8pkdQocNl5Y0+AObxCXdVBaXsxX45Wcqn9ztqJmruQr/nJvxKONrW+Lh9X542X69Vfjt38uwlqsMN/E0rllcGFrihUJBvgVXQ8n94jA+zL/
    e1ODSvWcJhpIEJzdO8s2I1y01PFfa+Dh59u8ffK62vN5hJ5+mD2kFOqqVuHFENBprZuSnhpF0Vx9uLNVwkn/4RbmlHYXE+zrTHYfI41RlQ9USpfCTbGtyDjP58D5rmnbXq/P7bi/d78mBw+EQG5FmhJsCs/z+9DA9yL8g6R61vxtpUAb4/ssKjEelmvS5yezCqw91PuG7BbTXDm73w/ROPyiy+SwWmdS9dL+zaz35ogI7E8Nj84k99Pg+f26Oc3Byn1Pi3eu6LYebRDQprUe37RkYwk0n/9KGpGuFbx+S4WmuCreWe7uFMJJn5oAMd54YG0q5TReKIXG4ZLNZKJfLP4X67cYcEG+/98TDUnrcbqtJSf08mH0NE8NIuisPDRgFIPneHALyYRhhbW0N355061xxSryu3UEMhqM910C6KfHF3htG0l15oI8M6Syb79fM5suVtrbNp5p7pKfBI49m14iHXssmu2+iWomey6Fs73BlKQW/v5xy7fbfaqOoVeBXf/56SztViibxznt5Ha4D6Eg63IWlWm/LLcAQb7uFD++jS0+gWK6AXw6AHBjuuKQdhewarBZz8LfrT+HVo9u23vUXCzyxwHPiuSUdhgpsmBWkG+pNSNBGsMJtmdVlSG7fy6T98Oebl/IZKBZy5j0wKlsuFxSfW7HIpd1dr4kndZF0c0JnHvqdf/ABvn40xpWvStWAXOrJ0CuZuqZCPrfK3+8Y88PhPaGOc0G9hiRJMDExgV73BZy/Xi4ajoiHoYIG0s2z8RGMUDv2w3vC8MIeM2RSLhchz1SwYaVdpVqBbPoJ/65B2QdvHIuBEtiaEp6RLR0MBs/h/BUkBC+6Snd899Br2bCZO5LuPRgh4MoZCMjw+rEx2B43q7xLhQxo5dJQft98epWplmZU/5vTYdieDIHfL2/VkqOzYMuawrzheDxupjSGQ337Tr5bd+51slrwJGdJkkaWdJZzBesJS6USZHJF+O9P1kDVq9zJMjaxa7jUaka41MpX/P3MZAi+eWSMJ7srbMLa5sFWAdp2U5s921KpyG3BdlTpQ1PO4ngdOVdwZRh10lkLEEp+XC1jzN47eTwGv76UhWA41iOiV6BcKnCJikRAe8tCQAnXPKtKqPv7vklMsimhKCQCJXh1Osa+s8LOqWxF0iEmhbQ7L/wR56EhZxifLaY6hkJhKBTyjIS92cCSSbz28kQjkTC/IAHcTPIXMOJAJ0u5XGarYxH+ficLN7Lbu064QjYFajHXJkn8EI4mur4A+Iwi/OsRDbYlcUKG+KIzRJXt7wsCNvVyaprGCFiAVi0AD00d6B3x8EYnEjW/yaxYLWhfOwbDMEBVS1w1+eNtBZbz3emmgWTDmJkTz6EcUCASn+Cv3cC39hVhartZwIwZSkPaTgKJgGmNC9CkcqZYLDACFrtGvLb0Bcy/FEiKlYFIJ4ClT4oS5Grn8V3lrngR0UOay6zyQK+Tz2taGTJrj/mr22uJKhXYPwEQZOrlkEm6Zmrou2wsCQdMXS8W1PaaZGc51yJubiLxGlRMlHS0w08TgxxVko+va3BjxZ3tg1KuVMjW/S4U8MGh7QHYPe6HaEiC8eh6v8zltM4r1p9mDbi5rD1ji8bHd7qWfCcOV2F6d3DU+nRiLucZIQHrnnUmk6mpntNOVc2NiIc3GlVMwXJcBd4hmjVHJq/Df/3VXZMidKDk0it1hPvWoSBM7zKDvbi7DX8VAycBDt60h73migZcvq/ClYflOvK5zazZx9b+N2dCo9qx7CKs5x3XkM/nuVe7J8TDeJ3oj4J23edEr9b49JYKn91zXqmAjpTUk4f8FXF8rwKzB8MQCspcxcPh90sgMQL5bB7FSsVgdmaF25q4CuPragalbwGWM+b1oLMlOjbh6vv9+2sKPDcmj/IjfqZ2D8m3d9cORwdreSfRiLY1JVogam2sarotZC3m0zXS/cPhMLx4wHRkKMK2Qu1Dskm8deJValLPYMTDPiLb2d9+b8YPv72U5eRDR004OsZDA05xZ1kfdeKhtjcHpkc/1eD7cEC8Fk6zcChs9VNFls8QvUxgAyGsR1thdhW20MONRdJFJq1cJq2gmom3+43pMLw0GeWLnhLA+FygRrh1ptu8Y6hC+sytrWU/SkaDvfr5z9+b8cFvFzPwmNl/hXzGldS7/KgCmqHCWESCeAiHDyZiI9eI/IRwvsyBy2Zdvpu37zaVdujBATPKjycYSS8mNiS6/kgX5MImRL0pAMXAeG5tGY7sDMDcNxI8VobEc+pFROmnlctQUlXI5kvw809SoBpo6+3r+rUnw+y4jIz7xk0S7hn3jwohf4SaYHLMmdRrqjvYVMyzMMKhg99dVuHuau+rrbVykTtS3jgWd006LgUxlxQTf4UD5nWmul68WgBdK3cttmchxRakVNGApVrPUA3ePC7D1A5l2KfHe27MsGeWJrQlBPFQnJ4eVdLhhF1axU7Dvf/PMHR4ZSoE8ah70tWRj2kuYUbkY/tjkGCSyTC0vnyf649KW65kyCn5Upm8U+LVh0yDwYD1/iyMXGOD9WEY/Wt2WmWq4aHdYQgqzKaT/WByzv13kCQfk3Ayz6985WAEDL3cl+9TLBvCUTQSc+V0KpM707Gq2bguKcEQHm4ORjxQbogq835gP7OLxsfC4Mfdk1A97OKxMfSADppDeyLw8VKpL9/JqFT5/fNJI+N8eXctk0uNj8Xaln5So1NFZJ3Pj7r3suowXcvJ2DshQ4Cpl73I+EeVFc2HaDgIOxJyf75TFUZF1axTOxn5TjkinrDtpkbZtmugX19GMqbwyoJeZYYgoTHEkIj4+6iCjSQWGPlmOyae2ChynghnSb3+jHAw0NP6NivdbMdYoD/faXSnTEKQb9PNLmsBdPSk8WBslYhXE3Z9AqqCPoyC9/CcPNWsX7mWoy34MNnkLJj5nZtLPEXh0g6dKpPEun4qmtAXQuA5cmWJlMz+4Mdr6dxcW8STZVIzG1Eul/oyUVfy/fk+mVJ/FpJ+hS0G3t5Lt1Y5bcSTLYlHEMinnkAxuwaaWgS9rPbMKFrJ9l5OoJfxfqq3xl1F10AtZPn9InDN8UxrG0+Qjqkis6Rm1gP7SRYLGQActQVK4Y4Kv+0VW925yfy/xwihahUIB3vnYFla6c5+3xgYx9QzbLpkH7pNylXDQZo8Jt5hUm9hPBFbako84VE7Rfdpc1gTrLGHZmL7PsfFpkUd4PYTHY7v603ZDUq7yw91V8TT1AJbf1ZrpUuEtnG2mQknY5jYL/sxx47UzDrHShUOPqfAnSft2StasQCKi+5en9414IW9lZ44Wr5a02Fp1Z28K7Pvh6lt7eDAtmAtb5MAp1fT2bMTifjSMzae6KUxS/doHUiAf5tJwD8eCcHkNhl2jkkbJgaXijlXicVrxQos3u2NU+KPN1WXSdwalNX8pn+XiPjg5f1BeGF/dFTbRGwk9ern1/Vbd7D2bjYQCFBrhzqVUuf9MnGv7IphiBSyKqzmDF6n9zSr8+7RTzIGL4tJFSoQS+4AWQm5Ou8PXw3xFLJu4fdfluDzB+4SvgvZVSbxzN6eWL60a0yGsbDER5yNWAhr8MxYpF+WeRtALG8aseZIm2GcSb1Uo3Nliu5LPazyKLR/sY8JFpfi2JE01a1926q1ZkM4Fn6/BsV8BmIBd8T75d9V+OFrEmyLS10h3Wf33ZEObTq1kINjuxT4zvMRCMr17Sf4Kxt4n3hqGiOeLeeXsA60886tE6+KnQN8s6SON6gC7L+AHOAtFOzdvHAiovfcIiLvdcKIeXRXEK4+VVzfxoJWhf/8UwF+8GIQju0JOLPHmCT+hKmXnz3oQmmTTwI/k+LTOwMQCSq13i9WOwrzvY87lqzf83/rcSbOFsSZeuKZIPuuhZ3XqC7ZpZw1UC19adKAJTXctXN/cEWFa491+O4LQd7jpF18+VCDPy+VYbXQvVm/+7lxOLLXxzUAv0jmbjYIG2JyNZWdnUjGF+3ES9J9aZ+MjZMMf941zmyzuAEPst1TsW491eHWH3Q4vkuGA8zum94hgyI/
    O8FXsuy8awYsPtAY4brv7n95j7knYnBr7hA0aOrmGZJ4XYK1X943dpbgQab7E/PyI50PuAwQDvhgT2JdCt9a6W2lfNBfhaM7fMPevr1fmKupmiJVjPZCcEk8zF5Jqf6emzVoA95c6V9bipLhg6cFGeJxPxHPPWaYujnF1M0lqdX2Q4TOsFYA+PjecE7Mi7cB9AqRrptST6ZSDvfAeN4vv1BhWLsdoKPmw6sqvPlymB62e8wKG4+o5xYfXikydWy4cxj/72sNdiZ88MokJUB3g3gSkGPFFT6/q8Llr0dDXf/dtTLcWtboobvDCYt4iyPYEaoreLCqw/9eK49Ux9EPLqs8TY7gYt48ejzFM1d0TbcaHRHates0A37+pxUAf3SkvnexXIVffboM//FPOyAYoFxMR6jCFA86kcRzYNctPoaVp095Xd4oSbxiLgU3vlqFT66s0CRwDpN4mGtI6GDBwnYNGZW/L2SegKGpjqdyBfczqBp9oY1eLvLzOf18uZgFVVTjr2RKtGC7IB6PnlcqFdzvmYLobQITo1+ejMLVR1gcWoVcehmiiR3g73AnHuxPUsqZlSJKOApKKNbxMdoBVo+Xi3lGvBL4JB+E4xMQCEY6OgaWBWH/GQvfnB7j94FKf5yBSzxd1xfpVnQm8Q7tDMNrB8zNO6tGFfKpZTDK5baEB1ZyF9IrUMqmar8rF/KQW33Mh652px2Yms9C5skDKKSe1o6J14o/28+92SgXGOkya7Wff/BSHJ4bI59AN4iXolvRwU0TdWdzxxNw6LkAn48VIflUXoneeh5jo6Ds6tdQLhVb/nu37EY8Dl5Xs38rMWmbz6zwhk4bHQO/T4FJOuvnk89H4PiBmNjViLJZXBGPYbGsaSO8KVeHDVt9ZqU1bv74g9kk7EmYueaodqI6ZlVrN1PX8oycFZtNPRGReOJzvSrbnbigac+tAxcJPF9NBWXkz7FFwNCat5xANdiuXr56IASvHIqDguVBcoB3WaP54GBB1PVadcIS5mxSSKE94EqPEg/LZFDtfPtbSfjFX1PwVdokjCUhrOZHWDyLkxjtLDu+cygM3z4c4ce49lCFLx6o/Bjo7OqG28Jymh3frcDxvUHYM6HwItmLV3Nw+VG59jcoqUPR5IbX+xoj3Ukm4YNix1qy7dyZKr5rN27j+1k5EPhc7HtO6OAG4l7jxVIJcoUSI99ajXyIQCjC1FI/d27YpRxKuO8fj8H0rgjfJciUTgZX+56ky/DJzQKsSLtcSjsdvpFIwexUBIIBiVfS47nwmnHTzcU7efjwWv1CgP1iUJI1Xi+Sbu5Fk3TYS4VKhNxhdXX1pyJXExbRtsCVjm5oJ5IP+I6rYQhyJQIl38fXc/DZvZJQ5QrPfGYvU0vfnInDeFThKhvuWYFtElD90Ji6v2tcgsnndFhe1l01ya2w4x14LgjRsFLrg+KXzE5pqlqGVw4Cb1b0m8s5KGqmfEWvp97QL9Qk3RgjXZDvLFu/Yy3ByWLNbBKwP9mLbPU+oQQpCbZjRwvuNS5+nnvRXLj+dq/0zN/iJH7jSBQiIYVLDqzqtlQ2vyAHqvwTcQ2MRzr4XBBP1zXYlghBJBIxd4KS1ncLQimMPx/ZI8G2MRk+uJStk9QWvvt8BF49GKupl3h9tDC7gyjDW5RtC9cFthKeUBQiXsfk80l80xfsXI7vTzaQD1XLuaNReHFfmE1ek3RWJy7eFAiJx8jA1UH2+Yl4kEmenKtWgdWKwY4TBoWdr7Fdg8wI7Qv6zHOx87/9mgSf3MjD3+7XX+9L+6NcKgfZnMDGT9TAqAt2t27gPUzZl9QLTOK9ww0/WtUcST70cgo9jJEPYP9EANIFA6Z3BmuqZZD9jbxB+zv8/c4k7kOfczXHxwIak6bN98SzmjhZPVRwzL0oweGdCixn9Nr1WpKObLruAc0JU+LZiMdGWlXVBK7IBCc2n6/OM3x0t8QdJtgWAtVKHJtNYqtlXlIxoOTiWpKhKpdQrc5lkY+fjw2VvZ/c7md2YZU7YazmRn4/xeu6TLz00SOHUo1GxPlSqXSaiOeefFbIATUIlDyoitrtrA2Jx0YiWIGiUXG0EQqmou3eG6hJ33YktY83bAqINLD2r5fQoX1Xrda196sRr2IYp1EcUkzPHfmQdJbjpOO+k+xvccOUG1cfdZxTiZ5prZSHsJJs+5zWYuH4egltoVzmsdPmxEN1s1gsJoh47snndPJan0UHCVYEOMHkjnDH56eemX0h3gV+r5v8+4LOJJ4wAgkeOWp2j7tT99FBQ1JrsNRMkZTAiSc38Zxhf/cfF4pFGCOp5wnwmWxPBiEckKCgdd5E6V8OR7mDxMqlJHiPkopbpcGl548cSjVTNRFLbFxkUu8E2XreqanhoAyn/3EbXHtU6OizuI3Wy1MJcowM0kJarUJZ5YXTC9bvWqVGnGXjo3w+D8kkbavgBfHQ0TE+FoLXwlLbld6mR1Sqy4ghDIC0K9Wq9c/XntWX12+1+nvURU9EolGg8EL/gW59tLV1sTdf28QTtYIU9B4caZdaW8NXVDNnN5N4NalXLBT4Ckoer/7Ciq/JYiuwTqQlhQMGUtqds/9e3qDvOEq8i+xDJ3LZLFDJkAcqJ6zvuOpgqaUbOADSrsQEF3uTtquZfGHd5LPz+D9UeZC5BAKhfRSLxZq0e/7odKoT4i2x8TN8U8jngXYWIhDaA3KlxIjHkG5UM9shnmXr3cU3uVyOeikSCG0AIwICz0i7domXslROjLxns1m6qwTCBigwu84wtcOm0q5d4iEuWCon2ns2NhMIBBsw6USomFxbbCbtEL4vr93s5LiYWT2Db8KRCITDtFEhgWAB463pVMoyxy4x0rXcAq/T4NwpIT4B43vlskp3m0AAM3SAZpjNBzK/0d/LHbpKlgT5PsIfstkcxOIA1KeFMOrIMNLZvP4/OXZ0esNtEXxXO1M17Wx+z/qB1E7CKAN9Hup6nPsiI93cZp9xmge2wMZPrR9Q7SSHC4FIx8Nup9r5nFOJZyfg6ZreGghAPB6nPEHCSNh0GDawkQ59H3ObqZjdIt4z5MMCzFgsxrPjCYRhJV0mk7FidRZOMtJdaPcY3Sg5mLernRhkz6TTlNtJGEqgAyXN5ncD6X7UCem6JfHsBHzP/gtsEYfSj0qKCMMADJ/lc3mo1jfUQNItdHqsbhIPMQdm+UNtW2dsqopeTyqmJWxl1RLzlLX6fQTRpjvjhHSCeDe6fZ1TgnwzdTqt5IdoNEo9XAjDIOXSQsgsHjt62NFxe6EDLrGBqTI/s/+yUsEE6ww3Sql1IGHQgXMU5yqvyKkn3SUxvxfdHL+XxtcZNk6CKClaN061dQKWy/SECQNJOJyjOFcb8L6QdEtuz9Nrr8eFZtKvRsBcFlKplL0vBYHgDeGYENiAcKhavg2mAzHVjfP1wsbbyPZDQ/REqz/AveNw11F8pSA8odfA0AC2VceBplALvC+0t6aEc2rj9TPKvSTENI6zzQiIXiPLc2RtbWVtI0wgdEuyaYJwG5ANcVHM0wu9uI5+SrxGzImV5K12/hiJaG0pLGOzVtH0lWKEhFa2miXVeI9S9moYbfUM6ohwTiWel8Szq6BnhP6coClD8Aj/A2abho4k3FYmnh2nbINISOg1Lgm/w3lw6Kl0TLwrg0U8O2YFAedgA4cMgeBAsl1wQzY7XtgCzpVOsQj1QcpZMabEa1K8n6S5RGhhq4Eg2VKT+eQp/l+AAQAokozXSzp6HQAAAABJRU5ErkJggg==" /></div>
                <h2><?php echo $translator->trans('welcome.header'); ?></h2>
                <h3><?php echo $translator->trans('welcome.content'); ?></h3>
                <div class="start-footer">
                    <button type="button" id="begin-install" class="primary button next" href="javascript: void(0);"><span><?php echo $translator->trans('welcome.button'); ?></span></button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>