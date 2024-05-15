# Query plugin for MantisBT

Version 2.17

Copyright 2012 - 2024 by Cas Nuy www.NUY.info

## Description

This plugin will enable you to define multiple queries/scripts, which can be:
1. run interactively (by admin)
2. scheduled, in which case results are emailed to nominated recipients

## Requirements

The plugin requires MantisBT version 2.0.0 or higher.

## Installation

As any other plugin, place the Query directory in your plugins directory.
After installing it within Mantis, click on the module to finalize the settings.

### PHP 8.2 

When using PHP 8.2 or later, a change is required to `vendor/adodb/adodb-php/drivers/adodb-mysqli.inc.php`.
Find the following lines

```
/*--------------------------------------------------------------------------------------
	 Class Name: Recordset
--------------------------------------------------------------------------------------*/
class ADORecordSet_mysqli extends ADORecordSet{
```
and make it look like this:
```
/*--------------------------------------------------------------------------------------
	 Class Name: Recordset
--------------------------------------------------------------------------------------*/
#[AllowDynamicProperties]
class ADORecordSet_mysqli extends ADORecordSet{
```

## Usage

### Query scripts

#### Samples
The distribution comes with 5 samples.
Please use the sql found in doc\plugin_Query_samples.txt to load them using a tool like phpmyadmin.

### PHP scripts

In case the report requirements go beyond a straightforward SQL query, you can also insert a PHP script to the query engine.
Your script should return the value that should be downloaded and contain no PHP tags.

A very simple script would look like:
```php
$to_download  = "Hello world";
$to_download .= "\r\n";
$to_download .= "Greetings, Cas";
return $to_download;
```

When adding the code to the system it will not try to trap any parsing errors so you should ensure that it does generate the required output.
Finally there is the option to add a script which handles everything itself, so no emailing is done by the system.

### Mailing

It uses a direct mail function hence SMTP host needs to be set correctly in php.ini

### Scheduling

Scheduling has 2 parts.

Within the plugin a set of jobs can be defined which should run as a type of batch job.
For each job the following  can be defined :

1. Description
2. Select query definition to be used
3. Optionally add an additional  filter for type Q/S
4. Define recipients (mantis usernames and/or normal email addresses separated by comma)
5. Define frequency

There are 4 frequencies available:

1. Every day
2. Weekdays only (Monday till Friday)
3. Weekly (Mondays)
4. Monthly (First day of the month)

Finally one job needs to be scheduled on your server to run daily, shortly after midnight.
This needs to be set up by your admin using either CRON or Windows Scheduled tasks.
In order to run in batch mode, one needs to schedule the following job:

exec_schedule.php (https://example.com/mantisbt/plugins/Query/pages/exec_schedule.php)

## Changelog

Version 1.00	17-08-2012<br>
&nbsp;&nbsp;&nbsp;&nbsp		Initial release<br>
Version 1.01	22-08-2012<br>	
&nbsp;&nbsp;&nbsp;&nbsp		Bugfixes<br>
&nbsp;&nbsp;&nbsp;&nbsp;	Added option for users to execute query directly<br>
&nbsp;&nbsp;&nbsp;&nbsp;	- Only type Q can be run<br>
Version 1.02	23-08-2012<br>
&nbsp;&nbsp;&nbsp;&nbsp		Bugfixes<br>
&nbsp;&nbsp;&nbsp;&nbsp;	Added sensible samples of each type of query<br>
&nbsp;&nbsp;&nbsp;&nbsp;	Allow standard email addresses in combination with mantis usernames<br>
Version 1.03<br>			
&nbsp;&nbsp;&nbsp;&nbsp		Bugfix Schedule<br>
&nbsp;&nbsp;&nbsp;&nbsp;	- frequency was not stored<br>
Version 1.04<br>		
&nbsp;&nbsp;&nbsp;&nbsp		Bugfix release<br>
Version 1.05<br>
&nbsp;&nbsp;&nbsp;&nbsp		Allowed for additional parameters when using S(cript)<br>
Version 1.06<br>	
&nbsp;&nbsp;&nbsp;&nbsp		Decent subject of emails<br>
Version 1.07<br>
&nbsp;&nbsp;&nbsp;&nbsp		Added option to define Query as User or Admin only<br>
version 1.08<br>
&nbsp;&nbsp;&nbsp;&nbsp		Fixed from email address, which is causing the email to be placed into the junk mailbox<br>
Version 2.01<br>
&nbsp;&nbsp;&nbsp;&nbsp		Mantis 2x compatible<br>
Version 2.10	01-05-2022<br>
&nbsp;&nbsp;&nbsp;&nbsp		Major bugfix release<br>
Version 2.11	07-01-2024<br>
&nbsp;&nbsp;&nbsp;&nbsp		Replaced print_successful_redirect<br>
Version 2.13	09-05-2024<br>
&nbsp;&nbsp;&nbsp;&nbsp		removed deprecated function<br>
Version 2.14	14-05-2024<br>
&nbsp;&nbsp;&nbsp;&nbsp		Lay-out corrections and added some help text<br>
Version 2.15	14-05-2024<br>
&nbsp;&nbsp;&nbsp;&nbsp		Bug fixes<br>
Version 2.16	15-05-2024<br>
&nbsp;&nbsp;&nbsp;&nbsp		Lay-out corrections and moved some help text<br>
&nbsp;&nbsp;&nbsp;&nbsp;	Moved Changelog to Readme<br>
&nbsp;&nbsp;&nbsp;&nbsp;	Made changes to lay-out (mantis standardization)<br>
Version 2.17	16-05-2024<br>
&nbsp;&nbsp;&nbsp;&nbsp		Removed User/Admin levels, handled via execution threshold)<br>
&nbsp;&nbsp;&nbsp;&nbsp		Included check on query uniqueness after editing query<br>