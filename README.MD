########################################################
# 	Mantis Bugtracker Add-On
# 	Query Version 2.11
#	2012 - 2023 plugin by Cas Nuy www.NUY.info
########################################################

Query plugin

This plugin will enable you to define multiple queries/scripts which:
1. can be run online (by admin)
2. can be scheduled in which case results are emailed to nominated recipients

Installation
As any other plugin, place the Query directory in your plugins directory.
After installing it within Mantis, click on the module to finaliuze the settings.

Samples
The distribution comes with 5 samples.
Please use the sql found in doc\plugin_Query_samples.txt to load them using a tool like phpmyadmin.

scripts
In case the report requirements go beyond a straight forward sql query, you can also insert a script to the query engine.
Your script should return the value that should be downloaded and contain no PHP tags.
A very simple script would look like:
$to_download  = "Hello world";
$to_download .= "\r\n";
$to_download .= "Greetings, Cas";
return $to_download;

When adding the code to the system it will not try to trap any parsing errors so you should ensure that it does generate the required output.
Finally there is the option to add a script which handles everything itself, so no emailing is done by the system.

Mailing
It uses a direct mail function hence smtp host needs to be set correctly in php.ini

Scheduling
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
1. exec_schedule.php (http://localhost/m1210/plugins/Query/pages/exec_schedule.php)
