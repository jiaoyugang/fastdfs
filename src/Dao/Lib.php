<?php
namespace FastDfs\Dao;

use Exception;

class Lib
{
    /**
     * fastdfs版本号
     */
    public function version()
    {
        return fastdfs_client_version();
    }

    /**
     * 获取连接的跟踪器服务器
     * @return 为成功返回assoc数组，为错误返回false；assoc数组，包括以下元素：ip_addr，port和sock
     */
    public function get_tracker()
    {
        $tracker_server = fastdfs_tracker_get_connection();
        if(!fastdfs_active_test($tracker_server)){
            FastDFSException::error();
        }
        return $tracker_server;
    }

    /**
     * 获取存储服务器信息以上传文件
     * @param string group_name
     * @param array tracker_server = 
     * [
     *      ip_addr,
     *      port,
     *      sock,
     * ]
     * @return array 返回assoc数组以获取成功，返回false表示错误。assoc数组包括元素：ip_addr，端口，袜子和store_path_index
     */
    public function get_storage($group_name='',$tracker_server=[])
    {
        return fastdfs_tracker_query_storage_store();
    }
    

    /**
     * 获取远程文件信息
     * @param $group_name
     * @param $remote_filename
     */
    public function get_file_info($group_name, $remote_filename)
    {
        return fastdfs_get_file_info($group_name, $remote_filename);
    }

    /**
     * 连接FastDFS服务器
     * 
     * ip_addr：服务器的IP地址
     * port：服务器的端口
     * sock: 套接字符
     */
    public function connect_server(string $ip_addr,int $port)
    {
        return fastdfs_connect_server($ip_addr,$port);
    }

    /**
     * @param $server_infoconnect_server返回的信息
     * 
     */
    public function fastdfs_disconnect_server($server_info)
    {
        return fastdfs_disconnect_server();
    }

    /**
     * 检测文件是否存在
     * @param string $group_name
     * @param string $remote_filename
     */
    public function exist_file($group_name, $remote_filename)
    {
        if(!$result = fastdfs_storage_file_exist($group_name,$remote_filename, 
            $this->get_tracker(),
            $this->get_storage())
            ){
                throw new \FastDfs\Dao\FastDFSException(FastDFSException::FILE_DELETION_EXCEP);
        }
        return ['state' => 200 ,'data' => 'yes','message' => 'successful'];
    }

    /**
     * 删除文件
     * @param string $group_name
     * @param string $remote_filename
     */
    public function delete_file($group_name, $remote_filename)
    {
        try{
            $tracker = $this->get_tracker();
            $storgae = $this->get_storage();
            $exist = $this->exist_file($group_name,$remote_filename,$tracker,$storgae);
            if(is_array($exist) && $exist['state'] == 200){
                if(!$result = fastdfs_storage_delete_file($group_name,$remote_filename,$tracker,$storgae)){
                    throw new \FastDfs\Dao\FastDFSException(FastDFSException::FILE_DELETION_EXCEP);
                }
                return ['state' => 200 ,'message' => 'successful'];
            }else{
                return $exist;
            }
        }catch(\Exception $exce){
            pp($exce->getMessage());
        }
        
    }


    /**
     * 上传文件
     * @param string $local_filename    本地文件名(绝对路径)
     * '/home/images/test.jpeg'
     * 
     * @param string $group_name        指定要存储文件的组名
     * 
     * @param string $file_ext_name     文件扩展名，不包含点号
     * 'jpeg'
     * 
     * @param array  $meta_list =       元数据assoc数组
     * [
     *      'width'=> 1024，'height'=> 768
     * ]
     * 
     * @param array  $tracker_server    跟踪器服务器assoc数组
     * [
     *      ip_addr,port,sock,
     * ]
     * 
     * @param array  $storage_server    存储服务器assoc数组
     * [
     *      ip_addr,port,sock,
     * ]
     * 
     * ------------------------------------------------------
     * 
     * @return array $result
     * [
     *      group_name => 'group1'
     *      filename   => 'M00/00/02/wKgo3WB9QAiACnS0AAF6aBNq18A837.jpg'
     * ]
     */
    public function upload_file($local_filename,$group_name,$file_ext_name='',$meta_list=[])
    {
        if(empty($local_filename)){
            throw new \FastDfs\Dao\FastDFSException(FastDFSException::FILE_NOT_EXIST);
        }

        if(empty($group_name)){
            // pp(fastdfs_client_version());
            throw new \FastDfs\Dao\FastDFSException(FastDFSException::FILE_GROUP_NOT_EXIST);
        }
        
        if(empty($file_ext_name)){
            $file_ext_name = pathinfo($local_filename,PATHINFO_EXTENSION);
        }

        // pp($this->get_storage());
        if(!$result = fastdfs_storage_upload_by_filename($local_filename,
                $file_ext_name,
                $meta_list,
                $group_name,
                $this->get_tracker(),
                $this->get_storage())
            ){
            throw new \FastDfs\Dao\FastDFSException(FastDFSException::FILE_UPLOAD_FAILED);
        }

        return ['state' => 200 ,'data' => $result ,'message' => 'successful'] ?? [];
    }


    /**
     * @param string $file_buff        指定要存储文件的组名
     * 
     * @param string $file_ext_name     文件扩展名，不包含点号
     * 'jpeg'
     * 
     * @param array  $meta_list =       元数据assoc数组
     * [
     *      'width'=> 1024，'height'=> 768
     * ]
     * 
     * @param array  $tracker_server    跟踪器服务器assoc数组
     * [
     *      ip_addr,port,sock,
     * ]
     * 
     * @param array  $storage_server    存储服务器assoc数组
     * [
     *      ip_addr,port,sock,
     * ]
     * 
     * ------------------------------------------------------
     * 
     * @return array $result
     * [
     *      group_name => 'group1'
     *      filename   => 'M00/00/02/wKgo3WB9QAiACnS0AAF6aBNq18A837.jpg'
     * ]
     */
    public function upload_file_content($file_buff,$group_name,$file_ext_name='',$meta_list=[])
    {
        $tracker = $this->get_tracker();
        $storgae = $this->get_storage();
        if(!$result = fastdfs_storage_upload_by_filebuff($file_buff,
                $file_ext_name,
                $meta_list,
                $group_name,
                $tracker,
                $storgae)
            ){
            throw new \FastDfs\Dao\FastDFSException(FastDFSException::FILE_UPLOAD_FAILED);
        }
        return ['state' => 200 ,'data' => $result ,'message' => 'successful'] ?? [];
    }
}


