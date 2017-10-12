@echo off
:label
E:\coreseek-4.1-win32\bin\indexer.exe -c E:\coreseek-4.1-win32\etc\csft.conf info_search_delta
E:\coreseek-4.1-win32\bin\indexer.exe -c E:\coreseek-4.1-win32\etc\csft.conf --merge info_search info_search_delta --rotate
pause
goto label
