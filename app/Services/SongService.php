<?php

namespace App\Services;

use Illuminate\Support\ServiceProvider;
use App\Models\Song;

class SongService
{
   
    public function search($searchString){
        $query = Song::query();
        $words = explode(' ', $searchString);
        foreach($words as $index=>$word){
            $query->where('path','LIKE',"%{$word}%");
        }
        return $query->get();
    }
    public function getById($id){
        return Song::find($id);
    }

    public function addSong($title, $path, $directoryIndexes, $fileIndex) {
        $song = new Song();
        $song->title = $title;
        $song->path = $path;
        $song->directoryIndexes = $directoryIndexes;
        $song->fileIndex = $fileIndex;
        $song->save();
    }
}
