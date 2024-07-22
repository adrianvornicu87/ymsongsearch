<?php

namespace App\Services;

class ScanService
{
    private SongService $songService;
    private YamahaService $yamahaService;

    public function __construct(SongService $songService, YamahaService $yamahaService)
    {
        $this->songService = $songService;
        $this->yamahaService = $yamahaService;
    }

    public function scan()
    {
        $this->yamahaService->goToRootDirectory();
        $this->scanCurrentDirectory("", "");
    }

    private function scanCurrentDirectory($pathToAddToDb, $directoryIndexes)
    {
        $index = 0;
        $returnedSize = 0;
        do {
            $listResponse = $this->yamahaService->makeListRequest($index);
            $listBody = json_decode($listResponse->body());
            $responseCode = $listBody->response_code;
            if ($responseCode) {
                die($responseCode);
            }
            $items = $listBody->list_info;
            $returnedSize = count($items);

            foreach ($items as $item) {
                if ($item->attribute & 2) {
                    $folderPath = $pathToAddToDb . '/' . $item->text;
                    $folderIndexes = trim($directoryIndexes . ' ' . $index);
                    $this->yamahaService->makeSelectRequest($index);
                    $this->scanCurrentDirectory($folderPath, $folderIndexes);
                    $this->yamahaService->makeReturnRequest();
                    $this->yamahaService->makeListRequest(0);
                }

                if ($item->attribute & 4) {
                    $songPath = $pathToAddToDb . '/' . $item->text;
                    $this->songService->addSong($item->text, $songPath, $directoryIndexes, $index);
                }

                $index++;
            }
        } while ($returnedSize == 8);

    }
}