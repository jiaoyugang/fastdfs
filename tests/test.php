<?php
require __DIR__.'/../vendor/autoload.php';
// $conn = fastdfs_connect_server('192.168.40.221',22122);
// pp(fastdfs_tracker_query_storage_store());
use FastDfs\FastDFS as FastDfsFastDFS;

$fastDFS = new FastDfsFastDFS("192.168.40.221",22122);

// $res = $fastDFS->upload_path("/home/files/201907315550094.jpg","haoshangji",
// 'jpeg',[
//     'height' => 50,
//     'width' => 50
// ]);
// pp($res);

// $res = $fastDFS->delete_file('group1','M00/00/02/wKgo3WB9XvuAWgIoAAF6aBNq18A48.jpeg');
// pp($res);


// buffer上传文件
$content = file_get_contents("https://2c.zol-img.com.cn/product/124_501x2000/978/cevEcNIkuOIWI.jpg");
$res = $fastDFS->upload_content($content,'group1','jpeg');
pp($res);