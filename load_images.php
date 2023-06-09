<?php
// load_images.php
$currentFolder = $_GET['folder'] ?? '';  // 获取传递的文件夹参数
$folder = './kim';  // 图片文件夹路径
if ($currentFolder!=$folder) {
    $folder = './kim/' . $currentFolder;  // 设置 $folder 为指定文件夹路径
} else {
    $folder = './kim';  // 默认图片文件夹路径
}

$perPage = 30;  // 每页显示的图片数量
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;  // 当前请求的页数
$start = ($page - 1) * $perPage;  // 起始索引

$files = scandir($folder);  // 扫描文件夹中的文件
$imageData = array();

// 从起始索引开始获取指定数量的图片数据
for ($i = $start; $i < $start + $perPage && $i < count($files); $i++) {
    $file = $files[$i];
    if(strpos($file, '.'))
    {
        if ($file !== '.' && $file !== '..')
        {
            $imagePath = $folder . '/' . $file;
            $imageData[] = array('src' => $imagePath);
        }
    }
}

// 将图片数据以 JSON 格式返回给前端
header('Content-Type: application/json');
echo json_encode($imageData);
