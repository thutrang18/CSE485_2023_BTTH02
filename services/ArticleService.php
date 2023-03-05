<?php 
  define('PATCH_ROOT_ARTICLESERVICE', dirname(__FILE__, 2));
  require_once(PATCH_ROOT_ARTICLESERVICE . '/configs/PBConnection.php');


class ArticleService
{

    protected $articles ;
    private $isSucsess;
    

    public function querySQL($sql)
    {
        $conn = new PBConnection();
        $conn = $conn->getConnect();
        $stmt = $conn->prepare($sql);
        $this->isSucsess=$stmt->execute();
        $this->articles = $stmt->fetchAll();
    }
    
    public function getAllArticles($sql){
        $this->querySQL($sql);
        foreach ($this->articles as  $row) {
            
            $article = new Article($row['ma_bviet'], $row['tieude'],  $row['tomtat'], $row['ten_bhat'],$row['ma_tloai'], $row['noidung'],$row['ma_tgia'] ,   $row['ngayviet'],$row['hinhanh']);
            array_push($this->articles,$article);
            
        }
        return $this->articles;
    }
    
    
   }
   public function add()
   {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
       
        $tieude = ($_POST["txt_tieude"]);
      $tenbaihat = ($_POST["txt_tenbaihat"]);
      $theloai =($_POST["option_Theloai"]);
      
      $tomtat = ($_POST["txt_tomtat"]);
      $noidung = ($_POST["txt_noidung"]);
      $tacgia =($_POST["option_tacgia"]);

      $target_dir = "assets\images";
      $hinhanhpath = basename($_FILES["hinhanh"]["name"])."";
      $target_file = $target_dir . $hinhanhpath;
      if(move_uploaded_file($_FILES["hinhanh"]["tmp_name"], $target_file)){
        echo("Hinh anh da duoc up load");
      }
      else{
        echo("Hinh anh ko duoc up load");
      }
      

    
    $addArticleSql = "INSERT INTO `baiviet`( `tieude`, `ten_bhat`, baiviet.ma_tloai, `tomtat`, `noidung`, baiviet.ma_tgia, `hinhanh`) VALUES ('$tieude','$tenbaihat',$theloai,'$tomtat','$noidung',$tacgia,'$hinhanhpath')";
     
          $this->querySQL($addArticleSql);
           if($this->isSucsess){
                header("Location: index.php?controller=article");
           }
         
    }
}
public function delete()
{
    $artical_id = $_GET["id"];


    $sql=" DELETE FROM baiviet WHERE baiviet.ma_bviet = $artical_id;";
    $this->querySQL($sql);
    if($this->isSucsess){
         header("Location: index.php?controller=article");
    }
}

