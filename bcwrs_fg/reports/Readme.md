# BCWRS FileGuard Reporting

## Reporting files
Reports are simple text files. They are named using date and time like in the following examples:
```
report_20170601_070000.txt
report_20170601_130000.txt
report_20170602_070000.txt
report_20170602_130000.txt
```

### Example report
The following text is an example of how the report files generally look.
```
*** Basecom Cyber Warfare Response Suite: FileGuard ***
    Report Yoursite.com at 2017-06-01 07:00:00
    Nothing to report.
    Total time needed: 0 ms
    Total memory usage: 4000 kBytes
```
In this case, nothing happened. Otherwise the full path of the changed file would appear as an
entry in the list.
