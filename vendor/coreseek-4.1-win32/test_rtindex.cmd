@CLS
@SET VERSION=4.0.1
@SET VERDATE=2011年1月12日
@SET VERCONF=csft_rtindex.conf
@SET VERINDEX=rtindex
@SET VERINFO=RT实时索引
@SET VERTEST=test_coreseek_rtindex.php
@IF NOT "%1" == "" @SET VERCONF=%1
@IF NOT "%2" == "" @SET VERINDEX=%2
@IF NOT "%3" == "" @SET VERINFO=%3
@IF NOT "%4" == "" @SET VERTEST=%4

@SET TITLE=coreseek-%VERSION% win32测试平台【%VERDATE%更新】【%VERINFO%测试】
@TITLE %TITLE%
@ECHO %TITLE%
@PROMPT coreseek-%VERSION% [ $D $T ] $_$$$G
@SET path=.;bin;%path%
@ECHO. && @ECHO 配置检查：indexer -c etc\%VERCONF%
@indexer -c etc\%VERCONF%
@ECHO. && @ECHO 最新使用文档，请查看：http://www.coreseek.cn/products/products-install/
@ECHO. && @ECHO 测试程序：请使用 api/%VERTEST% 测试搜索服务 && @ECHO 指令测试：按 CTRL+C，然后选择 N，进入命令行测试环境
@ECHO. && @ECHO 搜索服务：searchd  -c etc\%VERCONF% && @ECHO 服务说明：以下提示中中文可能不显示，但不影响PHP搜索
@searchd  -c etc\%VERCONF% 
@%ComSpec% /Q /K ECHO.
