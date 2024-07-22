<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\YamahaService;
use App\Services\SongService;
use App\Services\ScanService;
use Illuminate\Http\Request;
use App\Models\Song;

class SongController extends Controller
{
    private $yamahaService;
    private $songService;
    private $scanService;

    public function __construct(YamahaService $yamahaService, SongService $songService, ScanService $scanService)
    {
        $this->yamahaService = $yamahaService;
        $this->songService = $songService;
        $this->scanService = $scanService;
    }
    //
    public function create()
    {
        $song = new Song();
        $song->title = "Birt";
        $song->directoryIndexes = "2";
        $song->fileIndex = 7;
        $song->save();



    }

    public function search($searchString)
    {
        header("Access-Control-Allow-Origin:*");
        return $this->songService->search($searchString);
    }

    public function play($id)
    {
        $song = $this->songService->getById($id);
        return $this->yamahaService->play($song->directoryIndexes, $song->fileIndex);
    }

    public function scan()
    {
        return $this->scanService->scan();
    }

}
