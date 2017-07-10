# BCWRS
The Basecom Cyber Warfare Response Suite is a set of tools that provide advanced security measures for php web applications. 

## Disclaimer
All tools provided here are currently under development. Use on your own risk.

## Contents
The BCWRS is currently comprised of the following tools.

### FileGuard
FileGuard creates a database of sha1-hashes of all php files in a given directory. When rerun, it compares the current hashes to the previously taken hashes reports file modifications. FileGuard can report new, modified and deleted files. As a console application it is best implmented as a cron job.

See readme in bcwrs_fg directory for further details.

### Firewall
The firewall blocks http requests to the guarded web application by taking the country of origin into account. Based on the geographic origin, a block rule can protect control panels or other possibly vulnerable areas.

See readme in bcwrs_fw directory for further details.