<!DOCTYPE html>
<html>
<head>
    <title>overlook</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha1/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>

 .navbar {
            background-color: #FFC0CB;
            padding: 10px;
            color: #fff;
            font-size: 18px;
            display: flex;
            justify-content: center;
        }
        .navbar-title {
            color: #fff;
            font-weight: bold;
            font-size: 24px;
            margin-right: 20px;
            animation: wave 1.5s infinite;
            transform-origin: 70% 70%;
        }
        @keyframes wave {
            0% { transform: rotate(0deg); }
            10% { transform: rotate(5deg); }
            20% { transform: rotate(-5deg); }
            30% { transform: rotate(3deg); }
            40% { transform: rotate(-3deg); }
            50% { transform: rotate(2deg); }
            60% { transform: rotate(-2deg); }
            70% { transform: rotate(1deg); }
            80% { transform: rotate(-1deg); }
            90% { transform: rotate(0.5deg); }
            100% { transform: rotate(0deg); }
        }
        .navbar a {
            color: #fff;
            text-decoration: none;
            margin-right: 10px;
            padding: 10px 20px;
            border-radius: 30px;
            transition: background-color 0.3s ease;
        }
        .navbar a:hover {
            background-color: #fff;
            color: #FFC0CB;
        }
        body {
            background-color: #000;
        }
        .image-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: flex-start;
        }
        .image-container .image-wrapper {
            width: calc(33.33% - 10px);
            margin: 5px;
            background-color: rgba(0, 0, 0, 0.5);
            border-radius: 10px;
            overflow: hidden;
            position: relative;
            cursor: pointer;
        }
        .image-container .image-wrapper img {
            width: 100%;
            height: auto;
            object-fit: cover;
            border: 5px solid #FFC0CB;
            border-radius: 5px;
        }
        .loading-message {
            text-align: center;
            margin-top: 20px;
            color: #fff;
        }
    </style>
     <?php $currentFolder = $_GET['folder'] ?? ''; ?>
    <script>
        $(document).ready(function() {
            var isLoading = false;  // 标识当前是否正在加载新的图片
            var page = 1;  // 当前加载的页数
            
            function loadImages($ffa) {
                isLoading = true;
                
                // 显示加载信息
                $(".loading-message").show();
                
                // 发送 AJAX 请求，获取图片数据
                $.ajax({
                    url: "load_images.php?folder="+$ffa,
                    method: "GET",
                    data: { page: page },
                    dataType: "json",
                    success: function(data) {
                        // 隐藏加载信息
                        $(".loading-message").hide();
                        
                        // 根据返回的图片数据，动态创建图片容器
                        $.each(data, function(index, image) {
                            var imageWrapper = $("<div>").addClass("image-wrapper");
                            var img = $("<img>").attr("src", image.src).attr("data-full-src", image.src).attr("alt", "Image");
                            
                            imageWrapper.append(img);
                            $(".image-container").append(imageWrapper);
                        });
                        
                        // 更新页数
                        page++;
                        
                        isLoading = false;
                    },
                    error: function() {
                        isLoading = false;
                    }
                });
            }
            // 初始化加载第一页的图片
           
           
             $ffa = "<?php echo $currentFolder; ?>";
            
            loadImages($ffa);
            
            // 监听滚动事件，触发分批加载
            $(window).scroll(function() {
                if (!isLoading && $(window).scrollTop() + $(window).height() >= $(document).height() - 100) {
                    loadImages($ffa);
                }
            });
            
            // 点击图片时的事件处理函数
            $(".image-container").on("click", ".image-wrapper", function() {
                // 获取点击的图片源
                var imageSrc = $(this).find("img").attr("data-full-src");
                
                // 在新的页面中打开图片全屏显示
                window.open(imageSrc, "_blank");
            });
        });
    </script>
</head>
<body>
 <nav class="navbar">
       <a href="look.php" class="navbar-title">EasyPics</a>
        <?php
            $folder = './kim';  // 图片文件夹路径
            $directories = array_filter(glob($folder.'/*'), 'is_dir');  // 获取子文件夹列表

            foreach ($directories as $directory) {
                $directoryName = basename($directory);  // 获取文件夹名称
                echo '<a href="look.php?folder='.urlencode($directoryName).'">'.$directoryName.'</a>';
                //echo '<meta http-equiv="refresh" content="1;url=look.php?folder='.urlencode($directoryName).'">';
            }
        ?>
    </nav>
    <div class="container">
        <!--<h1 class="text-center">瀑布流示例</h1>-->
        <div class="image-container">
            <!-- 这里是瀑布流图片的内容 -->
        </div>
        <div class="loading-message" style="display: none;">加载中...</div>
    </div>
</body>
</html>
