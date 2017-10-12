@echo off
:label
D:\coreseek-4.1-win32\bin\indexer.exe -c D:\coreseek-4.1-win32\etc\csft_shopstore.conf shopstore_search_delta
D:\coreseek-4.1-win32\bin\indexer.exe -c D:\coreseek-4.1-win32\etc\csft_shopstore.conf --merge shopstore_search shopstore_search_delta --rotate
pause
goto label
