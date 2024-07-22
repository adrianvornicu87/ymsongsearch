*Yamaha Song Search*

This application was created for the users of Yamaha audio devices to help them search for songs on the USB stick. The Yamaha device itself does not have any search functionality, neither does the MusicCast app. The user could only play a song if he/she knew in which folder it was. And if you had nested folders it would also take some time to walk through them. This is where the Yamaha Song Search app comes in. In this repo it is written in PHP / Laravel and uses a mysql/mariadb database. 
The specification for the Yamaha device API is found within this repo, in YXC_API_Spec_Basic.pdf
This was just an exercise to practice Laravel and at the same time solve a problem that the author had.

*How it works*
The app scans the USB stick inserted into the Yamaha audio device and stores the songs in the database, for each song storing the steps needed to get to it. When you search for a song, the search is done in the database. When you want to play a song, requests are sent to the device to navigate to that song and play it.

*Installation*

git clone https://github.com/adrianvornicu87/ymsongsearch.git

In .env set YAMAHA_DEVICE_IP to the IP of your audio device in your home network

*To start the app*
If you only want to access the app from localhost in the browser or postman:
php artisan serve

If you also want to access the app from a device other than the one it's running on:
php artisan serve --host _IP of your computer in the home network_

*Routes*

- GET /scan

This starts the initial scan of the USB stick. It takes no parameters

- GET /search/{searchString}

This request performs the search and gives us the results like they are in the database in JSON format. Example for the search "eminem wi":
[
    {
        "id":1610,
        "created_at":"2024-07-22T05:57:44.000000Z",
        "updated_at":"2024-07-22T05:57:44.000000Z",
        "title":"Eminem-Without Me",
        "path":"/The Eminem Show/Eminem-Without Me",
        "directoryIndexes":"36 6 0",
        "fileIndex":16
    },
    {
		"id": 3431,
		"created_at": "2024-07-22T05:58:10.000000Z",
		"updated_at": "2024-07-22T05:58:10.000000Z",
		"title": "01-eminem-cold_wind_blows",
		"path": "/Eminem - Recovery/01-eminem-cold_wind_blows",
		"directoryIndexes": "45 2",
		"fileIndex": 0
	}
]

The search is done in the path column because it contains all the folders and the file name. 

 - GET /play/{id}
 
This request starts the actual playback of a song. It receives the ID of the song as the parameter. The playback is done by sending requests to the device to navigate between folders. As we can see from the Yamaha API specification PDF, we have to send requests to the device to navigate to the song and play it. For example if we access /play/1610 the app will instruct the audio device to do the following: 
 - Go to folder with index 36
 - Inside that folder go to folder with index 6
 - Inside that folder go to folder with index 0
 - Play file with index 16 


