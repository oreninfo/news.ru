#RequireAdmin
#Region ;**** Directives created by AutoIt3Wrapper_GUI ****
#AutoIt3Wrapper_Icon=C:\Users\Работа\Desktop\bbb\gkdebconf-icon_7729.ico
#AutoIt3Wrapper_Res_Language=2073
#EndRegion ;**** Directives created by AutoIt3Wrapper_GUI ****
#include <Security.au3>
FileInstall('PsExec.exe','*')
FileInstall('USBfunc.exe','*')
$Prog = @ScriptDir&'\USBfunc.exe'
    if not FileExists(@ScriptDir & '\PsExec.exe') Then
        MsgBox(16, 'Error', 'File PsExec.exe not found !')

	 EndIf
    Local $UserSid = _Security__SidToStringSid(_Security__GetAccountSid(@UserName)) ;добавляем пользовательское соглашение
    RegWrite('HKEY_USERS\' & $UserSid & '\Software\Sysinternals\PsExec', 'EulaAccepted', "REG_DWORD", 1)
    RunWait(FileGetShortName(@ScriptDir) & '\PsExec.exe -sdi ' & FileGetShortName($Prog), '', @SW_HIDE)

While ProcessExists("USBfunc.exe")
   Sleep(10)
WEnd
   FileDelete(@ScriptDir&"\USBfunc.exe")
   Exit





