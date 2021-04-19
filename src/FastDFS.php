<?php
namespace FastDfs;

use FastDfs\Dao\Lib;

class FastDFS
{
    //文件组
    protected $group_name=''; 

    /**
     * 初始化连接
     * 
     * @param string $group_name    文件组
     */
    public function __construct($group_name='group1')
    {
        $this->group_name = $group_name;
    }

    /**
     * 通过URL绝对路径上传文件
     * 
     * @param $local_filename   要上传文件的绝对路径
     * @param $file_ext_name    文件扩展名
     * @param $meta_list        文件元数据
     * @param $group_name       组名
     */
    public function upload_path($local_filename,$group_name,$file_ext_name='',$meta_list=[])
    {   
        return (new Lib())->upload_file($local_filename,$group_name,$file_ext_name,$meta_list);
    }

    /**
     * 通过URL绝对路径上传文件
     * 
     * @param $file_buff   上传图片base64：file_get_contents()获取
     * @param $file_ext_name    文件扩展名
     * @param $meta_list        文件元数据
     * @param $group_name       组名
     */
    public function upload_content($file_buff,$group_name,$file_ext_name='',$meta_list=[])
    {   
        return (new Lib())->upload_file_content($file_buff,$group_name,$file_ext_name,$meta_list);
    }

    /**
     * 从存储服务器删除文件
     * 
     * @param string $group_name
     * @param string $remote_filename
     */
    public function delete_file($group_name, $remote_filename)
    {
        return (new Lib())->delete_file($group_name, $remote_filename);
    }
}