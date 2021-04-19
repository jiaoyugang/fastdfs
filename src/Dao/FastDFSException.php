<?php
namespace FastDfs\Dao;

use Exception;

class FastDFSException extends Exception
{
    /**code => message**/
    const FILE_NOT_EXIST = 'file is not exist';
    const FILE_UPLOAD_FAILED ='file upload failed';
    const FILE_GROUP_NOT_EXIST = 'file group is null';
    const FILE_DELETION_EXCEP = 'File deletion exception';

    public function __construct($msg='')
    {
        if(!extension_loaded('fastdfs_client')){
            $this->error(1);
        }
        $this->error($msg);
    }

    /**
     * 捕获异常信息
     */
    static public function error($msg='')
    {
        // error_log("errno: " . fastdfs_get_last_error_no() . ", error info: " . fastdfs_get_last_error_info());exit(1);
        if($msg){
            echo json_encode(['state' => 40003 ,'info' => $msg,'successful' => 'failed'],JSON_UNESCAPED_UNICODE);exit(1);
        }else{
            echo json_encode(['state' => 40002 ,'info' => fastdfs_get_last_error_info(),'successful' => 'failed'],JSON_UNESCAPED_UNICODE);exit(1);
        }
       
    }
}