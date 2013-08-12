@ECHO OFF
CLS

SET MeP=no
IF /i "%1" EQU "mep" (
	SET MeP=yes
)

SET JumpToEnd=no
IF /i "%1" EQU "install" (
	SET JumpToEnd=yes
	GOTO ENV_INSTALL
)
IF /i "%1" EQU "dump" (
	SET JumpToEnd=yes
	GOTO ENV_DUMP
)
IF /i "%1" EQU "cache" (
    SET JumpToEnd=yes
    GOTO ENV_CACHE
)

:ENV_INSTALL
ECHO # ---------------------- #
ECHO # Env=Dev assets:install #
ECHO # ---------------------- #
 ..\..\php\php.exe app/console --env=dev assets:install
IF /i "%JumpToEnd%" EQU "yes" GOTO BATCH_END

:ENV_DUMP
ECHO # -------------------- #
ECHO # Env=Dev assetic:dump #
ECHO # -------------------- #
 ..\..\php\php.exe app/console --env=dev assetic:dump
IF /i "%JumpToEnd%" EQU "yes" GOTO BATCH_END

:ENV_CACHE
ECHO # ------------------- #
ECHO # Env=Dev clear:cache #
ECHO # ------------------- #
 ..\..\php\php.exe app/console --env=dev cache:clear --no-debug
IF /i "%JumpToEnd%" EQU "yes" GOTO BATCH_END


ECHO # ----------------------- #
ECHO # Env=Prod assets:install #
ECHO # ----------------------- #
 ..\..\php\php.exe app/console --env=prod assets:install

ECHO # --------------------- #
ECHO # Env=Prod assetic:dump #
ECHO # --------------------- #
 ..\..\php\php.exe app/console --env=prod assetic:dump

ECHO # -------------------- #
ECHO # Env=Prod clear:cache #
ECHO # -------------------- #
 ..\..\php\php.exe app/console --env=prod cache:clear --no-debug

:BATCH_END
pause