<?php
#+------------------------------------------------------------------
#| 普通的。
#+------------------------------------------------------------------
#| Author:Janmas Cromwell <janmas-cromwell@outlook.com>
#+------------------------------------------------------------------
namespace Janmas\M3u8;

use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;
use FFMpeg\Format\Video\X264;

class Generate
{
    /**
     * MP4文件地址
     * @var string
     */
    protected $videoPath = '';

    /**
     * 视频文件名
     * @var string
     */
    protected $videoName = '';
    /**
     * 是否加密
     * @var bool
     */
    protected $encryp = false;
    /**
     * m3u8文件保存文件夹地址
     * @var string
     */
    protected $saveDir = '';

    protected $ffmpegConfig = [];

    public function __construct(string $filePath, string $saveDir, bool $encryption = false)
    {
        if (!is_file($filePath)) {
            throw new \Exception(sprintf('文件%s不存在', basename($filePath)));
        }

        $this->videoPath = $filePath;
        $this->videoName = basename($filePath);
        if (!is_dir($saveDir)) {
            mkdir($saveDir, 0777, true);
        }

        $this->saveDir = $saveDir;

        $this->encryp = $encryption;
    }

    /**
     * @param array $ffmpegConfig
     */
    public function setFfmpegConfig(array $ffmpegConfig): void
    {
        $this->ffmpegConfig = $ffmpegConfig;
    }

    /*
-- 视频整体转码ts
ffmpeg -y -i music.mp4  -vcodec copy -acodec copy -vbsf h264_mp4toannexb out\music.ts
-- ts 文件切片
ffmpeg -i music.ts -c copy -map 0 -f segment -segment_list out\music.m3u8 -segment_time 15 out\15s_%3d.ts*/
    public function generate()
    {
        $ffmpeg = FFMpeg::create($this->ffmpegConfig);
        $video = $ffmpeg->open($this->videoPath);
        $clip = $video->clip(TimeCode::fromSeconds(0));
        $clip->save(new X264(), $this->videoName . '.ts');
        $file->$ffmpeg->open( 'your-file' );;
        $file->addFilter(
            new \FFMpeg\Filters\Audio\SimpleFilter([
                "-vn", "-hls_list_size", "0", "-hls_time", "60",
            ])
        );
        $file->save($audio_format, 'audio.m3u8');
    }


}
