<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use phpseclib3\Net\SSH2;
use phpseclib3\Net\SFTP;

class DownloadRecordings extends Controller
{
    public function index()
    {
        return view('downloadRecordings');
    }

    public function questionRecordings(Request $request)
    {
        $call_id = trim($request->query('id'));

        if (empty($call_id)) {
            return back()->with('message', 'Debe ingresar un ID.');
        }

        $command = "find /tmp/soul/Recordings/Records -name '*$call_id*'";
        $rutaRemota = trim($this->getSSHOutput($command));

        if (empty($rutaRemota)) {
            return back()->with('message', 'No se encontró la grabación.');
        }

        // Verificar si la carpeta existe, si no, la crea
        $directorio = storage_path("app/public/recordings");
        if (!file_exists($directorio)) {
            mkdir($directorio, 0777, true);
        }

        // Definir ruta local en Laravel
        $archivoNombre = basename($rutaRemota);
        $rutaLocal = "$directorio/$archivoNombre";

        // Descargar la grabación
        if (!$this->descargarArchivo($rutaRemota, $rutaLocal)) {
            return back()->with('message', 'Error al descargar la grabación.');
        }

        return view('downloadRecordings', compact('archivoNombre'));
    }

    private function getSSHOutput(string $command): ?string
    {
        $user = env('USER_SERVER');
        $password = env('PASSWORD_SERVER');
        $host_server = env('HOST_SERVER');

        $ssh = new SSH2($host_server);
        if (!$ssh->login($user, $password)) {
            dd("Error: No se pudo autenticar en SSH");
            return null;
        }

        return $ssh->exec($command);
    }

    private function descargarArchivo($rutaRemota, $rutaLocal)
    {
        $user = env('USER_SERVER');
        $password = env('PASSWORD_SERVER');
        $host_server = env('HOST_SERVER');

        $sftp = new SFTP($host_server);
        if (!$sftp->login($user, $password)) {
            dd("Error: No se pudo conectar por SFTP");
            return false;
        }

        if (!$sftp->file_exists($rutaRemota)) {
            dd("Error: El archivo no existe en el servidor:", $rutaRemota);
            return false;
        }

        $descargado = $sftp->get($rutaRemota, $rutaLocal);
        if (!$descargado) {
            dd("Error: Falló la descarga del archivo.");
            return false;
        }

        return true;
    }

    
    public function escucharGrabacion($archivo)
    {
        $rutaArchivo = storage_path("app/public/recordings/$archivo");
    
        if (!file_exists($rutaArchivo)) {
            return response()->json(['message' => 'Archivo no encontrado'], 404);
        }
    
        return response()->file($rutaArchivo, ['Content-Type' => 'audio/wav']);
    }
    
    public function descargarGrabacion($archivo)
    {
        $rutaArchivo = storage_path("app/public/recordings/$archivo");
    
        if (!file_exists($rutaArchivo)) {
            return response()->json(['message' => 'Archivo no encontrado'], 404);
        }
    
        return response()->download($rutaArchivo);
    }
    
}
