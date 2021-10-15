<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;

class QiepianController extends Controller
{
    public function sync()
    {
        $where = ['sync_data' => 1, 'is_move' => 1];
        $info = DB::connection('partner_guang')->table('program')->where($where)->first();
        if (!isset($info->path) && empty($info->path)) {
            return $this->setStatusCode(201)->success([]);
        }
        print_r($info->id);
        $data = [
            'program_name' => $info->program_name,
            'program_file' => $info->program_file,
            'program_coding' => $info->program_coding,
            'number_episode' => $info->number_episode,
            'type' => $info->type,
            'douban_link' => $info->douban_link,
            'imdb_link' => $info->imdb_link,
            'subtitles' => $info->subtitles,
            'zh_poster' => $info->zh_poster,
            'us_poster' => $info->us_poster,
            'category' => $info->category,
            'user_defined' => $info->user_defined,
            'us_defined_name' => $info->us_defined_name,
            'us_defined_content' => $info->us_defined_content,
            'path' => $info->path,
            'link' => $info->link,
            'status' => $info->status,
            'guid' => $info->guid,
            'store' => $info->store,
            'create_time' => strtotime($info->create_time),
            'update_time' => strtotime($info->update_time)
        ];
        $result = DB::connection('partner_my')->table('program')->insertGetId($data);
        if ($result) {
            DB::connection('partner_guang')->table('program')->where(['id' => $info->id])->update(['sync_data' => 1]);
        }

        return $this->setStatusCode(201)->success([]);
    }
}