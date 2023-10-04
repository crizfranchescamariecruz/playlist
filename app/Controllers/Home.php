<?php

namespace App\Controllers;

use App\Models\PlaylistModel;
use App\Models\MusicModel;
use App\Models\TracksModel;
use App\Controllers\BaseController;

class Home extends BaseController
{
    private $playlist;
    private $music;
    private $tracks;

    public function __construct()
    {
        $this->playlist = new PlaylistModel();
        $this->music = new MusicModel();
        $this->tracks = new TracksModel();
    }
    public function index()
    {
        $data['playlists'] = $this->playlist->findAll();
        $data['music'] = $this->music->findAll();
        return view('playerr', $data);
    }
    public function create()
    {
        $data = [
            'name' => $this->request->getPost('name')
        ];
        $this->playlist->insert($data);
        return redirect()->to('/playerr');
    }

    public function playlists($id)
    {
        $playlist = $this->playlist->find($id);

        if ($playlist) {
            $tracks = $this->tracks->where('p_id', $id)->findAll();
            $music = [];
            foreach ($tracks as $track) {
                $musicItem = $this->music->find($track['t_id']);
                if ($musicItem) {
                    $music[] = $musicItem;
                }
            }
            $data = [
                'playlist' => $playlist,
                'music' => $music,
                'playlists' => $this->playlist->findAll(),
                'tracks' => $tracks,
            ];
            return view('playerr', $data);
        } else {
            return redirect()->to('/playerr');
        }
    }

    public function search()
    {
        $search = $this->request->getGet('title');
        $musicResults = $this->music->like('title', '%' . $search . '%')->findAll();
        $data = [
            'playlists' => $this->playlist->findAll(),
            'music' => $musicResults,
        ];
        return view('playerr', $data);
    }
    public function add()
    {

        $musicID = $this->request->getPost('musicID');
        $playlistID = $this->request->getPost('playlist');

        $data = [
            'p_id' => $playlistID,
            't_id' => $musicID,
        ];
        $this->tracks->insert($data);
        return redirect()->to('/playerr');
    }

    public function upload()
    {
        $file = $this->request->getFile('file');
        $title = $this->request->getPost('title');
        $artist = $this->request->getPost('artist');
        $newName = $title . '_' . $artist . '.' . 'mp3';
        $file->move(ROOTPATH . 'public/', $newName);
        $data = [
            'title' => $title,
            'artist' => $artist,
            'file_p' => $newName
        ];
        $this->music->insert($data);
        return redirect()->to('/playerr');
    }
}
