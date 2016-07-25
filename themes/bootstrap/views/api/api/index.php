<?php
/* @var $this DefaultController */

$this->breadcrumbs=array(
	$this->module->id,
);
?>

<div class="page-header">
    <h1>API documentation</h1>
</div>

<h2>General scheme</h2>
&lt;errors&gt; - error codes [json] <br>
&lt;data&gt; - array of response data [json]
<h3>Errors</h3>
<div class="row">
    <table class="table table-bordered table-striped span6">
        <thead>
            <tr>
                <th>Code</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>0</td>
                <td>Without errors</td>
            </tr>
            <tr>
                <td>1</td>
                <td>Wrong parameters format</td>
            </tr>
        </tbody>
    </table>
</div>

<h3>Entities</h3>
<span class="label label-info">Chapter</span><br><br>
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th class="span3">Parameter</th>
            <th class="span3">Type</th>
            <th class="span3">Required</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>id</td>
            <td>int(11)</td>
            <td>yes</td>
            <td>Primary key</td>
        </tr>
        
         <tr>
            <td>title</td>
            <td>varchar(128)</td>
            <td>yes</td>
            <td>Title</td>
        </tr>
        
        <tr>
            <td>first_page</td>
            <td>int(4)</td>
            <td>yes</td>
            <td>First page number</td>
        </tr>
        
        <tr>
            <td>paid</td>
            <td>int(1)</td>
            <td>yes</td>
            <td>Bollean 1-yes, 0-no</td>
        </tr>
        <tr>
            <td>file</td>
            <td>varchar(255)</td>
            <td>no</td>
            <td>PDF file path path</td>
        </tr>
        <tr>
            <td>upload_date</td>
            <td>int</td>
            <td>no</td>
            <td>File upload timestamp</td>
        </tr>
    </tbody>
</table>

<h3>Requests</h3>

<h4>1) GET chapters</h4>
<strong>action:</strong> <span class="badge badge-success">chapters</span><br><br>
<strong>request:</strong>
<table class="table table-bordered">
    <thead>
        <tr>
            <th class="span3">Parameters</th>
            <th class="span3">Type</th>
            <th class="span3">Required</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>id</td>
            <td>int(11)</td>
            <td>no</td>
            <td>Chapter primary key</td>
        </tr>
        
        <tr>
            <td>limit</td>
            <td>int</td>
            <td>no</td>
            <td>Limit of returned models. Default value = 100</td>
        </tr>
        
        <tr>
            <td>offset</td>
            <td>int</td>
            <td>no</td>
            <td>Models offset. Default value = 0</td>
        </tr>
    </tbody>
</table>

<strong>response:</strong>
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th class="span3">Parameters</th>
            <th class="span3">Type</th>
            <th class="span3">Required</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>&lt;data&gt;</td>
            <td>json</td>
            <td>no</td>
            <td>json array containing Action entities</td>
        </tr>
        
        <tr>
            <td>&lt;errors&gt;</td>
            <td>json</td>
            <td>no</td>
            <td>
                json array containing error codes.<br>
                available errors codes: 1
            </td>
        </tr>
    </tbody>
</table>

















<?php 

?>