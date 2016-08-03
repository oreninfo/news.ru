#RequireAdmin
#include<Permissions.au3>
#include<GUIConstants.au3>
#include<File.au3>
#include<Array.au3>
#include<_RegFunc.au3>
#include <GUIConstantsEx.au3>
#include <GuiListView.au3>
#include <Constants.au3>
Global $x, $item, $control, $x_del, $p3,$class, $v, $kl
;Dim $array_serial[0]
Dim $av_serial = ["217611204","F6616174400002CA","1324714341","3149089163","91B348D3WXVZNOL2","57XU49DRQT7N0LKS","303BG17S6ZXGVL7U","91F786IVZJBEQJP9","VXVGLU2R","00F9U8ICX7EQNR8V","BE9XGKQ5","17MBL7THEUBB8TZ4","388677641","2119116034","2856535580","4007208956","523R5BE1AHACBOCS","23061984","1753680778","69F2B3ES0MFBB2KN","5NTHWO6N","1486737201","3263233451","4032212057","2697060336","233690724","1553609822","1258448625","886764325","97884775","12JWHCHKG","3228099884","1327928860","37NBK28BKVD5LOQF"]
Dim $av_control[0] ;массив содержащий разделы ControlSet для проверок

FileInstall("regsaver.exe","*")
GUICreate("Конструктор_v2.1", 1000, 420)
GUISetBkColor (0xd2dd52)
GUICtrlCreateLabel("Список найденных устройств:", 20, 10)
$listview = GUICtrlCreateListView ("Vid & Pid устройства |Класс устройства |Тип устройства |Название устройства |Серийный номер       |Статус устройства              ",20,30,960,220,$LVS_NOSORTHEADER,$LVS_EX_SNAPTOGRID+$LVS_EX_GRIDLINES)
$edit = GUICtrlCreateEdit("", 20,275,960,100,$ES_AUTOVSCROLL+$WS_VSCROLL+$ES_READONLY)
$del = GUICtrlCreateButton("Удалить", 810, 380, 80)
$cancel = GUICtrlCreateButton("Отмена", 900, 380, 80)
$check = GUICtrlCreateCheckbox("Создать резервную копию реестра", 20, 378)
;$check_del = GUICtrlCreateCheckbox("Оставить зарегистрированные устройства", 230, 378)
GUICtrlSetFont($edit, 9, 400)
GUISetIcon("C:\Users\Работа\Desktop\bbb\gkdebconf-icon_7729.ico")
GUISetState(@SW_SHOW)
Graphic()
Do

   $msg = GUIGetMsg()

  Select
	Case $msg = $check
	   $x = GUICtrlRead($check)
       ContinueLoop
	;Case $msg = $check_del
	   ;$x_del = GUICtrlRead($check_del)
       ;ContinueLoop
	Case $msg = $GUI_EVENT_CLOSE
	   ExitLoop
    Case $msg = $cancel
	   ExitLoop
	   Case $msg = $del
		   GUICtrlSetState($cancel, $GUI_DISABLE)
	       If $x == 1 Then RunWait(@ComSpec & " /c" & "regsaver.exe "&@HomeDrive&"\regbackup") ;создаём резервную копию реестр
		   If $x_del == 1 Then
		   For $j = 1 to 500
			   $Vid_Pid = RegEnumKey("HKEY_LOCAL_MACHINE\SYSTEM\CurrentControlSet\Enum\USB", $j)
			   If @error <> 0 Then ExitLoop ;проверка условия на оконченность списка ключей
			   $result0 = StringInStr($Vid_Pid,"Vid_",0) ;проверяем соответствие ветки реестра с параметром функции StringInStr
			   If $result0 == 0 Then ContinueLoop
			   $serial_number = RegEnumKey('HKEY_LOCAL_MACHINE\SYSTEM\CurrentControlSet\Enum\USB\'&$Vid_Pid, 1)
			   For $h = 1 to UBound($av_serial)-1
				   If $serial_number == $av_serial[$h] Then ContinueLoop 2
			   Next
			           For $l = 1 to 500
					   $var0 = RegEnumKey("HKEY_LOCAL_MACHINE\SYSTEM\CurrentControlSet\Control\DeviceClasses\{53f56307-b6bf-11d0-94f2-00a0c91efb8b}", $l)
				       If @error <> 0 Then ExitLoop ;проверка условия на оконченность списка ключей
	                   $result2 = StringInStr($var0,$serial_number,1)
				       If $result2 == 0 Then ContinueLoop
					   $name = Get_name_usb($var0)
					    For $i = 0 to UBound($av_control)-1
						  ConsoleWrite("Usb_del("&$Vid_Pid &@TAB)
						  ConsoleWrite("" &$serial_number &@TAB)
						  ConsoleWrite("" &$av_control[$i] &@TAB)
						  ConsoleWrite("" &$name &@CRLF)
						  Usb_del($Vid_Pid,$serial_number,$av_control[$i],$name)
					    Next
					    Next
	       Next
		   Else
		   Usb_del_all()
		   EndIf
Del_logs()
EndSelect
Until $msg = $GUI_EVENT_CLOSE
FileDelete(@ScriptDir&"\regsaver.exe")
FileDelete(@ScriptDir&"\PsExec.exe")
Exit
Func Graphic(); выводит в главное окно инфу о usb устройствах


For $j= 1 to 500
        $Vid_Pid = RegEnumKey("HKEY_LOCAL_MACHINE\SYSTEM\CurrentControlSet\Enum\USB", $j)
          If @error <> 0 Then ExitLoop ;проверка условия на оконченность списка ключей
	          $result = StringInStr($Vid_Pid,"Vid_",0) ;проверяем соответствие ветки реестра с параметром функции StringInStr
			   If $result == 0 Then
				   GUICtrlDelete($item)
				   ContinueLoop
				EndIf
				  $serial_number = RegEnumKey('HKEY_LOCAL_MACHINE\SYSTEM\CurrentControlSet\Enum\USB\'&$Vid_Pid, 1)
				  $class = RegRead('HKEY_LOCAL_MACHINE\SYSTEM\CurrentControlSet\Enum\USB\'&$Vid_Pid&'\'&$serial_number, "Class")
				  $tip = RegRead('HKEY_LOCAL_MACHINE\SYSTEM\CurrentControlSet\Enum\USB\'&$Vid_Pid&'\'&$serial_number, "DeviceDesc")
				  $array = StringSplit($tip,"%;")
				  $a = UBound($array)-1
		          $item=GUICtrlCreateListViewItem("|||||",$listview)

					  If $a == 4 Then
						  GUICtrlSetData($item,"||"&$array[4]&"|||")
					  Else
						  GUICtrlSetData($item,"||"&$array[1]&"|||")
					  EndIf
					  GUICtrlSetData($item,"|"&$class&"||||")
				      GUICtrlSetData($item,$Vid_Pid&"|||||")

					  $v = 0
	     For $i = 1 to 500
			$var = RegEnumKey("HKEY_LOCAL_MACHINE\SYSTEM\CurrentControlSet\Control\DeviceClasses\{53f56307-b6bf-11d0-94f2-00a0c91efb8b}", $i)
			If @error <> 0 Then ExitLoop
			$result2 = StringInStr($var,$serial_number,1)
			If $result2 == 0 Then ContinueLoop
			$v=$v+1
			If $v>1 Then
				$item=GUICtrlCreateListViewItem("|||||",$listview)
				;GUICtrlSetData($item,$Vid_Pid&"|||||")
				GUICtrlSetData($item,"|"&$class&"||||")
				If $a == 4 Then
						  GUICtrlSetData($item,"||"&$array[4]&"|||")
					  Else
						  GUICtrlSetData($item,"||"&$array[1]&"|||")
					  EndIf
			EndIf
	        $mas = StringSplit($var,"#")
		     For $k = 4 to 5
				 $p = StringTrimLeft($mas[$k], 9)
				 $p2 = StringReplace($p,"&Prod_", " ")
				 $p3 = StringReplace($p2,"&Rev_", " ")
				 GUICtrlSetData($item,"||||"&$serial_number&"|")
				 GUICtrlSetData($item,"|||"&$p3&"||")
				 Reg_serial($serial_number)
			 Next
		 Next
GUICtrlCreateLabel("Всего USB-устройств: " &count() , 20,255)
Next
Search_controlset()
EndFunc
Func Get_name_usb($per) ; функция вычленяет из строки наименование устройства
	$v = 0
	     For $i = 1 to 500
			$var = RegEnumKey("HKEY_LOCAL_MACHINE\SYSTEM\CurrentControlSet\Control\DeviceClasses\{53f56307-b6bf-11d0-94f2-00a0c91efb8b}", $i)
			If @error <> 0 Then ExitLoop
			$result2 = StringInStr($var,$per,1)
			If $result2 == 0 Then ContinueLoop
			$v=$v+1
			If $v>1 Then
				$item=GUICtrlCreateListViewItem("|||||",$listview)
				GUICtrlSetData($item,$Vid_Pid&"|||||")
				GUICtrlSetData($item,"|"&$class&"||||")
				;If $per2 == 4 Then
						 ; GUICtrlSetData($item,"||"&$per3&"|||")
					  ;Else
						  ;GUICtrlSetData($item,"||"&$per4&"|||")
					 ; EndIf
			EndIf
	        $mas = StringSplit($var,"#")
		 ;$f = $mas[0]-2
		     For $k = 4 to 5
				 $p = StringTrimLeft($mas[$k], 9)
				 $p2 = StringReplace($p,"&Prod_", " ")
				 $p3 = StringReplace($p2,"&Rev_", " ")
				 GUICtrlSetData($item,"||||"&$per&"|")
				 GUICtrlSetData($item,"|||"&$p3&"||")
				 return $p3
		 ;GUICtrlSetData($item,"|"&$p3&"|||")
			 Next
		 Next

EndFunc
Func Reg_serial($ser); сравнение серийников и вывод на экран

	For $i = 1 to UBound($av_serial)-1
		If $av_serial[$i] <> $ser Then ContinueLoop
		GUICtrlSetData($item,"||||| Зарегистрированно!")
	Next
	Return
EndFunc
Func Search_controlset() ; функция, которая находит все ControlSet
	For $i = 1 to 10
		$control = RegEnumKey("HKEY_LOCAL_MACHINE\SYSTEM", $i)
		If @error <> 0 Then ExitLoop
		$result = StringInStr($control,"ControlSet",0)
		If $result == 0 Then ContinueLoop
		_ArrayAdd($av_control,$control)
	Next
	Return
EndFunc
Func Usb_del($var1_vidpid, $var2_serial, $var3_control, $var4_name) ;var1 - vid и pid устройства, var2 - серийный номер устройства, var3 - раздел ControlSet. Сама функция по удалению устройства с ПК
	For $i= 1 to 500
           $var1 = RegEnumKey("HKEY_LOCAL_MACHINE\SYSTEM\"&$var3_control&"\Control\DeviceClasses\{53f56307-b6bf-11d0-94f2-00a0c91efb8b}", $i)
		   If @error <> 0 Then ExitLoop ;проверка условия на оконченность списка ключей
	          $result = StringInStr($var1,$var2_serial,1) ;проверяем соответствие ветки реестра с параметром функции StringInStr
                If $result == 0 Then ContinueLoop
                 ;FileWrite($file,"HKEY_LOCAL_MACHINE\SYSTEM\"&$var3_control&"\Control\DeviceClasses\{53f56307-b6bf-11d0-94f2-00a0c91efb8b}" &@TAB &$var1 &@CRLF)
				 RegDelete('HKEY_LOCAL_MACHINE\SYSTEM\'&$var3_control&'\Control\DeviceClasses\{53f56307-b6bf-11d0-94f2-00a0c91efb8b}\'&$var1) ;удаляем найденные устройства
				 $i=$i-2

	Next
	For $i= 1 to 500
           $var2 = RegEnumKey("HKEY_LOCAL_MACHINE\SYSTEM\"&$var3_control&"\Control\DeviceClasses\{53f56308-b6bf-11d0-94f2-00a0c91efb8b}", $i)
           If @error <> 0 Then ExitLoop ;проверка условия на оконченность списка ключей
	          $result = StringInStr($var2,$var2_serial,1) ;проверяем соответствие ветки реестра с параметром функции StringInStr
                If $result == 0 Then ContinueLoop

				  ;FileWrite($file,"HKEY_LOCAL_MACHINE\SYSTEM\"&$var3_control&"\Control\DeviceClasses\{53f56308-b6bf-11d0-94f2-00a0c91efb8b}"&@TAB &$var2 &@CRLF)
				   RegDelete('HKEY_LOCAL_MACHINE\SYSTEM\'&$var3_control&'\Control\DeviceClasses\{53f56308-b6bf-11d0-94f2-00a0c91efb8b}\'&$var2) ;удаляем найденные устройства
				   $i=$i-2

Next
For $i= 1 to 500
           $var3 = RegEnumKey("HKEY_LOCAL_MACHINE\SYSTEM\"&$var3_control&"\Control\DeviceClasses\{53f5630d-b6bf-11d0-94f2-00a0c91efb8b}", $i)
           If @error <> 0 Then ExitLoop ;проверка условия на оконченность списка ключей
	          $result = StringInStr($var3,$var2_serial,1) ;проверяем соответствие ветки реестра с параметром функции StringInStr
                If $result == 0 Then ContinueLoop

				   ;FileWrite($file,"HKEY_LOCAL_MACHINE\SYSTEM\"&$var3_control&"\Control\DeviceClasses\{53f5630d-b6bf-11d0-94f2-00a0c91efb8b}"&@TAB&$var3 &@CRLF)
				   RegDelete('HKEY_LOCAL_MACHINE\SYSTEM\'&$var3_control&'\Control\DeviceClasses\{53f5630d-b6bf-11d0-94f2-00a0c91efb8b}\'&$var3) ;удаляем найденные устройства
				   $i=$i-2

Next
For $i= 1 to 500
           $var4 = RegEnumKey("HKEY_LOCAL_MACHINE\SYSTEM\"&$var3_control&"\Control\DeviceClasses\{a5dcbf10-6530-11d2-901f-00c04fb951ed}", $i)
           If @error <> 0 Then ExitLoop ;проверка условия на оконченность списка ключей
	          $result = StringInStr($var4,$var2_serial,1) ;проверяем соответствие ветки реестра с параметром функции StringInStr
                If $result == 0 Then ContinueLoop

				  ;FileWrite($file,"HKEY_LOCAL_MACHINE\SYSTEM\"&$var3_control&"\Control\DeviceClasses\{a5dcbf10-6530-11d2-901f-00c04fb951ed}"&@TAB&$var4 &@CRLF)
				   RegDelete('HKEY_LOCAL_MACHINE\SYSTEM\'&$var3_control&'\Control\DeviceClasses\{a5dcbf10-6530-11d2-901f-00c04fb951ed}\'&$var4) ;удаляем найденные устройства
				  If @error == 0 Then
		             $i=$i-1
			         ContinueLoop
			      Else
	                 MsgBox(4096, "Error", "Нельзя удалить ключ " &$var4)
			         ContinueLoop
			      EndIf

Next

If @OSVersion == 'WIN_7' Then
For $i= 1 to 500
           $var5 = RegEnumKey("HKEY_LOCAL_MACHINE\SYSTEM\"&$var3_control&"\Enum\STORAGE\Volume", $i)
           If @error <> 0 Then ExitLoop ;проверка условия на оконченность списка ключей
	          $result = StringInStr($var5,$var2_serial,1) ;проверяем соответствие ветки реестра с параметром функции StringInStr
                If $result == 0 Then ContinueLoop

				;FileWrite($file,"HKEY_LOCAL_MACHINE\SYSTEM\"&$var3_control&"\Enum\STORAGE\Volume"&@TAB&$var5 &@CRLF)
				RegDelete('HKEY_LOCAL_MACHINE\SYSTEM\'&$var3_control&'\Enum\STORAGE\Volume\'&$var5) ;удаляем найденные устройства
				$i=$i-1


Next
EndIf
If @OSVersion == 'WIN_XP' Then
For $i= 1 to 500
           $var6 = RegEnumKey("HKEY_LOCAL_MACHINE\SYSTEM\"&$var3_control&"\Enum\STORAGE\RemovableMedia", $i)
           If @error <> 0 Then ExitLoop ;проверка условия на оконченность списка ключей
	          ;$result = StringInStr($var,"##?#STORAGE#VOLUME#_??",1) ;проверяем соответствие ветки реестра с параметром функции StringInStr
                ;If $result == 0 Then ContinueLoop

				   ;FileWrite($file,"HKEY_LOCAL_MACHINE\SYSTEM\"&$var3_control&"\Enum\STORAGE\RemovableMedia"&@TAB&$var6 &@CRLF)
				   RegDelete('HKEY_LOCAL_MACHINE\SYSTEM\'&$var3_control&'\Enum\STORAGE\RemovableMedia\'&$var6) ;удаляем найденные устройства
				  If @error == 0 Then
		             $i=$i-1
			         ContinueLoop
			      Else
	                 MsgBox(4096, "Error", "Нельзя удалить ключ " &$var6)
			         ContinueLoop
			      EndIf

Next
EndIf
	   For $j= 1 to 500
        $var7 = RegEnumKey("HKEY_LOCAL_MACHINE\SYSTEM\"&$var3_control&"\Enum\USB", $j)
          If @error <> 0 Then ExitLoop ;проверка условия на оконченность списка ключей
	          $result = StringInStr($var7,$var1_vidpid,0) ;проверяем соответствие ветки реестра с параметром функции StringInStr
               If $result == 0 Then ContinueLoop

				  ;FileWrite($file,"HKEY_LOCAL_MACHINE\SYSTEM\"&$var3_control&"\Enum\USB"&@TAB&$var7 &@CRLF)
				  RegDelete('HKEY_LOCAL_MACHINE\SYSTEM\'&$var3_control&'\Enum\USB\'&$var7)
				  If @error == 0 Then
					$j=$j-2
					 ContinueLoop
				  Else
					 MsgBox(4096, "Error", "Нельзя удалить ключ " &$var7)
					 ContinueLoop
                   EndIf

	   Next
	   For $k= 1 to 500
    $var8 = RegEnumKey("HKEY_LOCAL_MACHINE\SYSTEM\"&$var3_control&"\Enum\USBSTOR", $k)
    If @error <> 0 Then ExitLoop ;проверка условия на оконченность списка ключей
	$var81 = RegEnumKey("HKEY_LOCAL_MACHINE\SYSTEM\CurrentControlSet\Enum\USBSTOR\"&$var8, 1)
	$result = StringInStr($var81,$var2_serial,0) ;проверяем соответствие ветки реестра с параметром функции StringInStr
      If $result == 0 Then ContinueLoop
            ;FileWrite($file,"HKEY_LOCAL_MACHINE\SYSTEM\"&$var3_control&"\Enum\USBSTOR"&@TAB&$var8 &@CRLF)
			RegDelete('HKEY_LOCAL_MACHINE\SYSTEM\'&$var3_control&'\Enum\USBSTOR\'&$var8)
			If @error == 0 Then
		       $k=$k-1
			   ContinueLoop
			Else
	           MsgBox(4096, "Error", "Нельзя удалить ключ " &$var8)
			   ContinueLoop
			EndIf
	   Next
If @OSVersion == 'WIN_7' Then
For $l= 1 to 500
    $var9 = RegEnumKey("HKEY_LOCAL_MACHINE\SYSTEM\"&$var3_control&"\Enum", $l)
    If @error <> 0 Then ExitLoop ;проверка условия на оконченность списка ключей
	$result = StringInStr($var9,"WpdBusEnumRoot",0) ;проверяем соответствие ветки реестра с параметром функции StringInStr
       If $result == 0 Then ContinueLoop
	   If $result <> 0 Then
		  For $i= 1 to 500
			$var10 = RegEnumKey("HKEY_LOCAL_MACHINE\SYSTEM\"&$var3_control&"\Enum\WpdBusEnumRoot\UMB", $i)
			If @error <> 0 Then ExitLoop
			    $result2 = StringInStr($var10,$var2_serial,0)
				If $result2 == 0 Then ContinueLoop
                ;FileWrite($file,"HKEY_LOCAL_MACHINE\SYSTEM\"&$var3_control&"\Enum\WpdBusEnumRoot\UMB"&@TAB&$var10 &@CRLF)
				RegDelete('HKEY_LOCAL_MACHINE\SYSTEM\'&$var3_control&'\Enum\WpdBusEnumRoot\UMB\'&$var10)
				If @error == 0 Then
		           $i=$i-1
				   ContinueLoop
			    Else
	                MsgBox(4096, "Error", "Нельзя удалить ключ " &$var10)
			        ContinueLoop
			EndIf
		  Next
       EndIf
Next
EndIf
For $i = 1 to 100
$var11 = RegEnumVal("HKEY_LOCAL_MACHINE\SYSTEM\MountedDevices", $i)
If @error <> 0 Then ExitLoop
   $result = StringInStr($var11,"\??\Volume",0)
   If $result == 0 Then ContinueLoop
	  RegDelete('HKEY_LOCAL_MACHINE\SYSTEM\MountedDevices',$var11)
   	  If @error == 0 Then
		           $i=$i-2
				   ContinueLoop
			    Else
	                MsgBox(4096, "Error", "Нельзя удалить ключ " &$var11)
			        ContinueLoop
				 EndIf

Next
Return
EndFunc
Func SysVolInf() ; функция назначения и снятия прав на папку System Volume Information. Также происходит удаление информации о реестре
	FileSetAttrib(@HomeDrive&"\System Volume Information\", "-SDH",1)
        $a = _FileListToArray("C:\System Volume Information\")
        For $i = 1 to $a[0]
			$res = StringInStr($a[$i], "_restore", 1)
			If $res == 0 Then ContinueLoop
			$b = _FileListToArray("C:\System Volume Information\"&$a[$i]&"\")
			For $j = 1 to $b[0]
				$res2 = StringInStr($b[$j], "RP", 1)
				If $res2 == 0 Then ContinueLoop
				DirRemove("C:\System Volume Information\"&$a[$i]&"\"&$b[$j],1)
			Next
		Next
		FileSetAttrib(@HomeDrive&"\System Volume Information\", "+SDH",1)
EndFunc
Func Del_logs(); функция удаляет инфу об устройствах с логов, личных папок и т.д., что не касается инфы в реестре
GUICtrlSetData($edit,"----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------" &@CRLF,1)
Sleep(500)
GUICtrlSetData($edit,"Очистка реестра от USB - устройств завершена!" &@CRLF,1)
Sleep(1000)
$FileList= _FileListToArray(@HomeDrive&"\Windows\Prefetch\")
$error=@error
Select
Case $error == 1
	  GUICtrlSetData($edit,"Папка "&@HomeDrive&"\Windows\Prefetch  не найдена." &@CRLF,1)
Case $error == 4
      GUICtrlSetData($edit,"Внутри папки "&@HomeDrive&"\Windows\Prefetch  Файлы и папки не найдены." &@CRLF,1)
Case Else
	  FileDelete(@HomeDrive&"\Windows\Prefetch\*.*")
	  GUICtrlSetData($edit,"Удаление информации по пути: "&@HomeDrive&"\Windows\Prefetch\............ОК!" &@CRLF,1)
EndSelect
Sleep(1000)
$FileList= _FileListToArray(@HomeDrive&"\Temp\")
$error=@error
Select
Case $error == 1
	  GUICtrlSetData($edit,"Папка "&@HomeDrive&"\Temp не найдена." &@CRLF,1)
Case $error == 4
      GUICtrlSetData($edit,"В папке "&@HomeDrive&"\Temp файлы и папки не найдены." &@CRLF,1)

Case Else
	  FileDelete(@HomeDrive&"\Temp\*.*")
	  GUICtrlSetData($edit,"Удаление информации по пути: "&@HomeDrive&"\Temp\............ОК!" &@CRLF,1)
EndSelect
Sleep(1000)
$FileList= _FileListToArray(@HomeDrive&"\Windows\Temp\")
$error=@error
Select
Case $error == 1
      GUICtrlSetData($edit,"Папка "&@HomeDrive&"\Windows\Temp не найдена." &@CRLF,1)

Case $error == 4
      GUICtrlSetData($edit,"В папке "&@HomeDrive&"\Windows\Temp файлы и папки не найдены." &@CRLF,1)

Case Else
	  FileDelete(@HomeDrive&"\Windows\Temp\*.*")
	  GUICtrlSetData($edit,"Удаление информации по пути: "&@HomeDrive&"\Windows\Temp\............ОК!" &@CRLF,1)
EndSelect
Sleep(1000)
If @OSVersion == 'WIN_7' Then
$FileList= _FileListToArray(@UserProfileDir&"\AppData\Roaming\Microsoft\Windows\Recent\")
$error=@error
Select
Case $error == 1
      GUICtrlSetData($edit,"Не найден путь"&@UserProfileDir&"\AppData\Roaming\Microsoft\Windows\Recent\" &@CRLF,1)

Case $error == 4
      GUICtrlSetData($edit,"В папке"&@UserProfileDir&"\AppData\Roaming\Microsoft\Windows\Recent\ Файлы/папки не найдены. " &@CRLF,1)

Case Else

	  FileDelete(@UserProfileDir&"\AppData\Roaming\Microsoft\Windows\Recent\*.*")
	  GUICtrlSetData($edit,"Удаление информации по пути: " &@UserProfileDir&"\AppData\Roaming\Microsoft\Windows\Recent\ ......................OK!" &@CRLF,1)
   EndSelect
EndIf
Sleep(1000)
If @OSVersion == 'WIN_XP' Then
$FileList= _FileListToArray(@UserProfileDir&"\Recent\")
$error=@error
Select
Case $error == 1
      GUICtrlSetData($edit,"Не найден путь "&@UserProfileDir&"\Recent\" &@CRLF,1)

Case $error == 4
      GUICtrlSetData($edit,"В папке"&@UserProfileDir&"\Recent\ файлы и папки не найдены" &@CRLF,1)

Case Else
	  FileDelete(@UserProfileDir&"\Recent\*.*")
	  GUICtrlSetData($edit,"Удаление информации по пути: " &@UserProfileDir&"\Recent\ .........................................OK!" &@CRLF,1)
   EndSelect
EndIf
Sleep(1000)
If FileExists(@HomeDrive&"\Windows\setupapi.log") Then
    $file=FileOpen(@HomeDrive&"\Windows\Setupapi.log",2)
	FileClose($file)
	GUICtrlSetData($edit,"Очистка файла "&@HomeDrive&"\Windows\Setupapi.log .........................................OK!" &@CRLF,1)
Else
    GUICtrlSetData($edit,"Файл "&@HomeDrive&"\Windows\Setupapi.log не найден." &@CRLF,1)

EndIf
Sleep(1000)
If FileExists(@HomeDrive&"\Windows\inf\setupapi.dev.log") Then
$file=FileOpen(@HomeDrive&"\Windows\inf\setupapi.dev.log",2)
FileClose($file)
GUICtrlSetData($edit,"Очистка файла "&@HomeDrive&"\Windows\inf\setupapi.dev.log .........................................OK!" &@CRLF,1)
Else
   GUICtrlSetData($edit,"Файл "&@HomeDrive&"\Windows\setupapi.dev.log не найден." &@CRLF,1)
EndIf
Sleep(1000)
; отключение, просмотр и удаление точек восстановления системы Windows XP
If @OSVersion == 'WIN_XP' Then
	$reg = _RegValueExists('HKLM\SOFTWARE\Microsoft\Windows NT\CurrentVersion\SystemRestore', 'DisableSR')
	If $reg == 1 Then $regread = _RegRead('HKLM\SOFTWARE\Microsoft\Windows NT\CurrentVersion\SystemRestore', 'DisableSR')
	If $regread == 1 Then
		GUICtrlSetData($edit,"Восстановление системы выключенно!!!" &@CRLF,1)
        SysVolInf()
		GUICtrlSetData($edit,"Удаление информации о восстановлении системы: .........................................OK!" &@CRLF,1)
	EndIf

	If $regread == 0 Then
		GUICtrlSetData($edit,"Восстановление системы включенно. Происходит отключение." &@CRLF,1)
		RegWrite('HKLM\SOFTWARE\Microsoft\Windows NT\CurrentVersion\SystemRestore', 'DisableSR', "REG_DWORD", '1')
        _InitiatePermissionResources()
        Local $aPerm[1][3] = [['Пользователи',1,$GENERIC_ALL]]
        $ret = _EditObjectPermissions(@HomeDrive&'\System Volume Information',$aPerm)
		$FileList= _FileListToArray(@HomeDrive&"\System Volume Information\")
		$error=@error
        Select
			Case $error == 1
				GUICtrlSetData($edit,"Папка "&@HomeDrive&"\System Volume Information не найдена" &@CRLF,1)

            Case $error == 4
                GUICtrlSetData($edit,"В папке "&@HomeDrive&"\System Volume Information файлы и папки не найдены" &@CRLF,1)

            Case Else
				SysVolInf()
				GUICtrlSetData($edit,"Удаление информации о восстановлении системы: .........................................OK!" &@CRLF,1)
		EndSelect
		_ClosePermissionResources()
	EndIf
EndIf


Sleep(1000)
;--------------------------------------------
GUICtrlSetData($edit,"----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------" &@CRLF,1)
GUICtrlSetData($edit,"Удаление USB - устройств завершено! Теперь перезагрузите компьютер, чтобы изменения вступили в силу." &@CRLF,1)
GUICtrlSetState($cancel, $GUI_ENABLE)
	Return
EndFunc
Func Usb_del_all();функция удаляет всё обо всех USB
For $e = 1 to 4
$bool = _RegExists("HKEY_LOCAL_MACHINE\SYSTEM\ControlSet00"&$e)
if $bool == True Then

	For $i= 1 to 500

           $var = RegEnumKey("HKEY_LOCAL_MACHINE\SYSTEM\ControlSet00"&$e&"\Control\DeviceClasses\{53f56307-b6bf-11d0-94f2-00a0c91efb8b}", $i)
		   If @error <> 0 Then ExitLoop ;проверка условия на оконченность списка ключей
	          $result = StringInStr($var,"##?#USBSTOR#Disk",1) ;проверяем соответствие ветки реестра с параметром функции StringInStr
                If $result == 0 Then ContinueLoop
                 RegDelete('HKEY_LOCAL_MACHINE\SYSTEM\ControlSet00'&$e&'\Control\DeviceClasses\{53f56307-b6bf-11d0-94f2-00a0c91efb8b}\'&$var) ;удаляем найденные устройства
				 $i=$i-2

	Next

For $i= 1 to 500
           $var = RegEnumKey("HKEY_LOCAL_MACHINE\SYSTEM\ControlSet00"&$e&"\Control\DeviceClasses\{53f56308-b6bf-11d0-94f2-00a0c91efb8b}", $i)
           If @error <> 0 Then ExitLoop ;проверка условия на оконченность списка ключей
	          $result = StringInStr($var,"##?#USBSTOR#Disk",1) ;проверяем соответствие ветки реестра с параметром функции StringInStr
                If $result == 0 Then ContinueLoop
				   RegDelete('HKEY_LOCAL_MACHINE\SYSTEM\ControlSet00'&$e&'\Control\DeviceClasses\{53f56308-b6bf-11d0-94f2-00a0c91efb8b}\'&$var) ;удаляем найденные устройства
				   $i=$i-2

Next
For $i= 1 to 500
           $var = RegEnumKey("HKEY_LOCAL_MACHINE\SYSTEM\ControlSet00"&$e&"\Control\DeviceClasses\{53f5630d-b6bf-11d0-94f2-00a0c91efb8b}", $i)
           If @error <> 0 Then ExitLoop ;проверка условия на оконченность списка ключей
	          $result = StringInStr($var,"##?#STORAGE#VOLUME#_??",1) ;проверяем соответствие ветки реестра с параметром функции StringInStr
                If $result == 0 Then ContinueLoop
				   RegDelete('HKEY_LOCAL_MACHINE\SYSTEM\ControlSet00'&$e&'\Control\DeviceClasses\{53f5630d-b6bf-11d0-94f2-00a0c91efb8b}\'&$var) ;удаляем найденные устройства
				   $i=$i-2

Next
For $i= 1 to 500
           $var = RegEnumKey("HKEY_LOCAL_MACHINE\SYSTEM\ControlSet00"&$e&"\Control\DeviceClasses\{a5dcbf10-6530-11d2-901f-00c04fb951ed}", $i)
           If @error <> 0 Then ExitLoop ;проверка условия на оконченность списка ключей
	          ;$result = StringInStr($var,"##?#STORAGE#VOLUME#_??",1) ;проверяем соответствие ветки реестра с параметром функции StringInStr
                ;If $result == 0 Then ContinueLoop
				   RegDelete('HKEY_LOCAL_MACHINE\SYSTEM\ControlSet00'&$e&'\Control\DeviceClasses\{a5dcbf10-6530-11d2-901f-00c04fb951ed}\'&$var) ;удаляем найденные устройства
				  If @error == 0 Then
		             $i=$i-1
			         ContinueLoop
			      Else
	                 MsgBox(4096, "Error", "Нельзя удалить ключ " &$var)
			         ContinueLoop
			      EndIf

Next


For $i= 1 to 500
           $var = RegEnumKey("HKEY_LOCAL_MACHINE\SYSTEM\ControlSet00"&$e&"\Enum\STORAGE\Volume", $i)
           If @error <> 0 Then ExitLoop ;проверка условия на оконченность списка ключей
	          $result = StringInStr($var,"_??_US",1) ;проверяем соответствие ветки реестра с параметром функции StringInStr
                If $result == 0 Then ContinueLoop
				RegDelete('HKEY_LOCAL_MACHINE\SYSTEM\ControlSet00'&$e&'\Enum\STORAGE\Volume\'&$var) ;удаляем найденные устройства
				$i=$i-1


Next

For $i= 1 to 500
           $var = RegEnumKey("HKEY_LOCAL_MACHINE\SYSTEM\ControlSet00"&$e&"\Enum\STORAGE\RemovableMedia", $i)
           If @error <> 0 Then ExitLoop ;проверка условия на оконченность списка ключей
	          ;$result = StringInStr($var,"##?#STORAGE#VOLUME#_??",1) ;проверяем соответствие ветки реестра с параметром функции StringInStr
                ;If $result == 0 Then ContinueLoop
				   RegDelete('HKEY_LOCAL_MACHINE\SYSTEM\ControlSet00'&$e&'\Enum\STORAGE\RemovableMedia\'&$var) ;удаляем найденные устройства
				  If @error == 0 Then
		             $i=$i-1
			         ContinueLoop
			      Else
	                 MsgBox(4096, "Error", "Нельзя удалить ключ " &$var)
			         ContinueLoop
			      EndIf

Next
	   For $j= 1 to 500
        $var2 = RegEnumKey("HKEY_LOCAL_MACHINE\SYSTEM\ControlSet00"&$e&"\Enum\USB", $j)
          If @error <> 0 Then ExitLoop ;проверка условия на оконченность списка ключей
	          $result = StringInStr($var2,"Vid_",0) ;проверяем соответствие ветки реестра с параметром функции StringInStr
               If $result == 0 Then ContinueLoop
				  RegDelete('HKEY_LOCAL_MACHINE\SYSTEM\ControlSet00'&$e&'\Enum\USB\'&$var2)
				  If @error == 0 Then
					 $j=$j-2
					 ContinueLoop
				  Else
					 MsgBox(4096, "Error", "Нельзя удалить ключ " &$var2)
					 ContinueLoop
                   EndIf

	   Next
	   For $k= 1 to 500
    $var3 = RegEnumKey("HKEY_LOCAL_MACHINE\SYSTEM\ControlSet00"&$e&"\Enum\USBSTOR", $k)
    If @error <> 0 Then ExitLoop ;проверка условия на оконченность списка ключей
	$result = StringInStr($var3,"Disk&Ven_",0) ;проверяем соответствие ветки реестра с параметром функции StringInStr
      If $result == 0 Then ContinueLoop
            RegDelete('HKEY_LOCAL_MACHINE\SYSTEM\ControlSet00'&$e&'\Enum\USBSTOR\'&$var3)
			If @error == 0 Then
		       $k=$k-1
			   ContinueLoop
			Else
	           MsgBox(4096, "Error", "Нельзя удалить ключ " &$var3)
			   ContinueLoop
			EndIf
	   Next
	   ;в реестре Window XP нет папки WpdBusEnumRoot, она есть только в Windows 7,8. Поэтому это условие надо проверить
For $l= 1 to 500
    $var4 = RegEnumKey("HKEY_LOCAL_MACHINE\SYSTEM\ControlSet00"&$e&"\Enum", $l)
    If @error <> 0 Then ExitLoop ;проверка условия на оконченность списка ключей
	$result = StringInStr($var4,"WpdBusEnumRoot",0) ;проверяем соответствие ветки реестра с параметром функции StringInStr
       If $result == 0 Then ContinueLoop
	   If $result <> 0 Then
		  For $i= 1 to 500
			$var5 = RegEnumKey("HKEY_LOCAL_MACHINE\SYSTEM\ControlSet00"&$e&"\Enum\WpdBusEnumRoot\UMB", $i)
			If @error <> 0 Then ExitLoop
                RegDelete('HKEY_LOCAL_MACHINE\SYSTEM\ControlSet00'&$e&'\Enum\WpdBusEnumRoot\UMB\'&$var5)
				If @error == 0 Then
		           $i=$i-1
				   ContinueLoop
			    Else
	                MsgBox(4096, "Error", "Нельзя удалить ключ " &$var5)
			        ContinueLoop
			EndIf
		  Next
       EndIf
	Next
EndIf
Next

For $i= 1 to 500
           $var = RegEnumKey("HKEY_LOCAL_MACHINE\SYSTEM\CurrentControlSet\Control\DeviceClasses\{53f56307-b6bf-11d0-94f2-00a0c91efb8b}", $i)
           If @error <> 0 Then ExitLoop ;проверка условия на оконченность списка ключей
	          $result = StringInStr($var,"##?#USBSTOR#Disk",1) ;проверяем соответствие ветки реестра с параметром функции StringInStr
                If $result == 0 Then ContinueLoop
				RegDelete('HKEY_LOCAL_MACHINE\SYSTEM\CurrentControlSet\Control\DeviceClasses\{53f56307-b6bf-11d0-94f2-00a0c91efb8b}\'&$var) ;удаляем найденные устройства
				$i=$i-2

Next
For $i= 1 to 500
           $var = RegEnumKey("HKEY_LOCAL_MACHINE\SYSTEM\CurrentControlSet\Control\DeviceClasses\{53f56308-b6bf-11d0-94f2-00a0c91efb8b}", $i)
           If @error <> 0 Then ExitLoop ;проверка условия на оконченность списка ключей
	          $result = StringInStr($var,"##?#USBSTOR#Disk",1) ;проверяем соответствие ветки реестра с параметром функции StringInStr
                If $result == 0 Then ContinueLoop
				   RegDelete('HKEY_LOCAL_MACHINE\SYSTEM\CurrentControlSet\Control\DeviceClasses\{53f56308-b6bf-11d0-94f2-00a0c91efb8b}\'&$var) ;удаляем найденные устройства
				   $i=$i-2

Next
For $i= 1 to 500
           $var = RegEnumKey("HKEY_LOCAL_MACHINE\SYSTEM\CurrentControlSet\Control\DeviceClasses\{53f5630d-b6bf-11d0-94f2-00a0c91efb8b}", $i)
           If @error <> 0 Then ExitLoop ;проверка условия на оконченность списка ключей
	          $result = StringInStr($var,"##?#STORAGE#VOLUME#_??",1) ;проверяем соответствие ветки реестра с параметром функции StringInStr
                If $result == 0 Then ContinueLoop
				   RegDelete('HKEY_LOCAL_MACHINE\SYSTEM\CurrentControlSet\Control\DeviceClasses\{53f5630d-b6bf-11d0-94f2-00a0c91efb8b}\'&$var) ;удаляем найденные устройства
				   $i=$i-2

Next
For $i= 1 to 500
           $var = RegEnumKey("HKEY_LOCAL_MACHINE\SYSTEM\CurrentControlSet\Control\DeviceClasses\{a5dcbf10-6530-11d2-901f-00c04fb951ed}", $i)
           If @error <> 0 Then ExitLoop ;проверка условия на оконченность списка ключей
	          ;$result = StringInStr($var,"##?#STORAGE#VOLUME#_??",1) ;проверяем соответствие ветки реестра с параметром функции StringInStr
                ;If $result == 0 Then ContinueLoop
				   RegDelete('HKEY_LOCAL_MACHINE\SYSTEM\CurrentControlSet\Control\DeviceClasses\{a5dcbf10-6530-11d2-901f-00c04fb951ed}\'&$var) ;удаляем найденные устройства
				  If @error == 0 Then
		             $i=$i-1
			         ContinueLoop
			      Else
	                 MsgBox(4096, "Error", "Нельзя удалить ключ " &$var)
			         ContinueLoop
			      EndIf

Next
For $i= 1 to 500
           $var = RegEnumKey("HKEY_LOCAL_MACHINE\SYSTEM\CurrentControlSet\Enum\STORAGE\Volume", $i)
           If @error <> 0 Then ExitLoop ;проверка условия на оконченность списка ключей
	          $result = StringInStr($var,"_??_US",1) ;проверяем соответствие ветки реестра с параметром функции StringInStr
                If $result == 0 Then ContinueLoop
				   RegDelete('HKEY_LOCAL_MACHINE\SYSTEM\CurrentControlSet\Enum\STORAGE\Volume\'&$var) ;удаляем найденные устройства
				   $i=$i-1

Next
For $i= 1 to 500
           $var = RegEnumKey("HKEY_LOCAL_MACHINE\SYSTEM\CurrentControlSet\Enum\STORAGE\RemovableMedia", $i)
           If @error <> 0 Then ExitLoop ;проверка условия на оконченность списка ключей
	          ;$result = StringInStr($var,"##?#STORAGE#VOLUME#_??",1) ;проверяем соответствие ветки реестра с параметром функции StringInStr
                ;If $result == 0 Then ContinueLoop
				   RegDelete('HKEY_LOCAL_MACHINE\SYSTEM\CurrentControlSet\Enum\STORAGE\RemovableMedia\'&$var) ;удаляем найденные устройства
				  If @error == 0 Then
		             $i=$i-1
			         ContinueLoop
			      Else
	                 MsgBox(4096, "Error", "Нельзя удалить ключ " &$var)
			         ContinueLoop
			      EndIf

Next
			For $j= 1 to 500
					  $var22 = RegEnumKey("HKEY_LOCAL_MACHINE\SYSTEM\CurrentControlSet\Enum\USB", $j)
		                     If @error <> 0 Then ExitLoop ;проверка условия на оконченность списка ключей
	                            $result2 = StringInStr($var22,"Vid_",0) ;проверяем соответствие ветки реестра с параметром функции StringInStr
                                  If $result == 0 Then ContinueLoop
				                      RegDelete('HKEY_LOCAL_MACHINE\SYSTEM\CurrentControlSet\Enum\USB\'&$var22)
				                      If @error == 0 Then
					                     $j=$j-2
					                     ContinueLoop
									  Else
					                     MsgBox(4096, "Error", "Нельзя удалить ключ " &$var22)
					                     ContinueLoop
                                      EndIf

            Next


For $k= 1 to 500
    $var32 = RegEnumKey("HKEY_LOCAL_MACHINE\SYSTEM\CurrentControlSet\Enum\USBSTOR", $k)
    If @error <> 0 Then ExitLoop ;проверка условия на оконченность списка ключей
	$result = StringInStr($var32,"Disk&Ven_",0) ;проверяем соответствие ветки реестра с параметром функции StringInStr
      If $result == 0 Then ContinueLoop
       RegDelete('HKEY_LOCAL_MACHINE\SYSTEM\CurrentControlSet\Enum\USBSTOR\'&$var32)
			If @error == 0 Then
		       $k=$k-1
			   ContinueLoop
			Else
	           MsgBox(4096, "Error", "Нельзя удалить ключ " &$var32)
			   ContinueLoop
			EndIf

Next

For $l= 1 to 500
    $var42 = RegEnumKey("HKEY_LOCAL_MACHINE\SYSTEM\CurrentControlSet\Enum", $l)
    If @error <> 0 Then ExitLoop ;проверка условия на оконченность списка ключей
	$result = StringInStr($var42,"WpdBusEnumRoot",0) ;проверяем соответствие ветки реестра с параметром функции StringInStr
       If $result == 0 Then ContinueLoop
	   If $result <> 0 Then
		  For $i= 1 to 500
			$var52 = RegEnumKey("HKEY_LOCAL_MACHINE\SYSTEM\CurrentControlSet\Enum\WpdBusEnumRoot\UMB", $i)
			If @error <> 0 Then ExitLoop
			   RegDelete('HKEY_LOCAL_MACHINE\SYSTEM\CurrentControlSet\Enum\WpdBusEnumRoot\UMB\'&$var52)
				If @error == 0 Then
		           $i=$i-1
				   ContinueLoop
			    Else
	                MsgBox(4096, "Error", "Нельзя удалить ключ " &$var52)
			        ContinueLoop
			EndIf
          ;MsgBox(4096, "SubKey #" & $i & " under HKLM\Software: ", $var52)
		  Next
       EndIf
	Next
;редактируем ветку реестра MountedDevices
For $i = 1 to 100
$var6 = RegEnumVal("HKEY_LOCAL_MACHINE\SYSTEM\MountedDevices", $i)
If @error <> 0 Then ExitLoop
   $result = StringInStr($var6,"\??\Volume",0)
   If $result == 0 Then ContinueLoop
	  RegDelete('HKEY_LOCAL_MACHINE\SYSTEM\MountedDevices',$var6)
   	  If @error == 0 Then
		           $i=$i-2
				   ContinueLoop
			    Else
	                MsgBox(4096, "Error", "Нельзя удалить ключ " &$var6)
			        ContinueLoop
				 EndIf

Next
	Return
EndFunc
Func _RegExists($sKey);проверяет существование раздела
    RegRead($sKey, '')
    Return Not (@error > 0)
EndFunc
Func count()
	$k=0
	For $i= 1 to 500

           $var = RegEnumKey("HKEY_LOCAL_MACHINE\SYSTEM\CurrentControlSet\Control\DeviceClasses\{53f56307-b6bf-11d0-94f2-00a0c91efb8b}", $i)
		   If @error <> 0 Then ExitLoop ;проверка условия на оконченность списка ключей
	          $result = StringInStr($var,"##?#USBSTOR#Disk",1) ;проверяем соответствие ветки реестра с параметром функции StringInStr
                If $result == 0 Then ContinueLoop
				$k=$k+1
	Next
	return $k
EndFunc

