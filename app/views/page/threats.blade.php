@extends('layout.pages')

@section('title')
    <title>spreadit.io :: you serve your master well. and you will be rewarded.</title>
@stop
@section('description')
    <meta name="description" content="has spreadit received any legal threats?">
@stop

@section('content')
<div class="row-fluid">
    <div class="span12">
        <h2>Legal threats and action taken</h2>
        <table>
            <thead>
                <tr>
                    <td>Date</td>
                    <td>Link</td>
                    <td>Threat</td>
                    <td>Action Taken</td>
                    <td>Comments</td>
                </tr>
            </thead>
            <tbody>
                <tr><td>Nothing has occurred so far, lets hope it doesn't</td></tr>
                <tr><td>Just in case: It's been
                <?php
                $days_since_request = function() {
                    $now = time();
                    $your_date = strtotime("2014-01-01");
                    $datediff = $now - $your_date;
                    return floor($datediff / (60 * 60 * 24));
                };
                echo $days_since_request();
                ?>
                days since the last request for information</td></tr>
            </tbody>
        </table>
    </div>
</div>
@stop
