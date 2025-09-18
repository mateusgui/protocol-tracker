Set objShell = WScript.CreateObject("WScript.Shell")
command = WScript.Arguments.Item(0)

objShell.Run command, 0, false