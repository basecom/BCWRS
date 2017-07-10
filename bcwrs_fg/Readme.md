# BCWRS FileGuard
FileGuard creates a database of sha1-hashes of all php files in a given directory. When rerun, it compares the current hashes to the previously taken hashes reports file modifications. FileGuard can report new, modified and deleted files. As a console application it is best implmented as a cron job.

## Installation
Copy the bcwrs_fg directory somewhere outside of your web root, so the webserver does not expose the files to the public.

### Configuration
Open the configuration file "config.fileguard.php" and configure the path to your application root and an email address for reporting.

### Run as cron job
Running FileGuard as a cron job is the best option to automate reporting on modified files. The first entry will run FileGuard twice a day. The second cleans the reporting directory of old reports.

```
0 7,13 * * 1-5 php /var/www/vhosts/yoursite/files/bcwrs_fg/fileguard.php > /dev/null 2>&1
30 4 * * 1-5 cd /var/www/vhosts/yoursite/files/bcwrs_fg/reports && find . -mtime +90 -delete
```

## Reporting
Every time FileGuard is run, it will create a report. The report shows all changes made to php files including new and deleted ones. Reports are saved in the "reports" directory as a single text file per report. The naming of the files includes date and time. All results are saved here, even if the report shows no changes to any files. 