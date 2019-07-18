<?php

namespace app\controllers;

use Yii;
use app\models\Asset;
use app\models\AssetSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use PHPExcel;
use PHPExcel_IOFactory;
use yii\helpers\Html;
use yii\helpers\Url;
use mPDF;

/**
 * AssetController implements the CRUD actions for Asset model.
 */
class AssetController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Asset models.
     * @return mixed
     */
    public function actionPdf()

{

    $mpdf = new mPDF('th', 'A4', '0', 'Garuda'); // ขนาด A4 font Garuda

    $mpdf->WriteHTML($this->renderPartial('_reportView')); // หน้า View สำหรับ export

    $mpdf->Output();

    exit();

}


    public function actionExcel() {
        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel(); //สร้างไฟล์ excel
        // Add some data
        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A2', 'ครุภัณฑ์ทั้งหมด') //กำหนดให้ cell A2 พิมพ์คำว่า Employees Report
        ->setCellValue('A4', 'ประเภทครุภัณฑ์') //กำหนดให้ cell A4 พิมพ์คำว่า employeeNumber
        ->setCellValue('B4', 'หมายเลขครุภัณฑ์') //กำหนดให้ cell B4 พิมพ์คำว่า firstName
        ->setCellValue('C4', 'ชื่อครุภัณฑ์') //กำหนดให้ cell C4 พิมพ์คำว่า lastName
        ->setCellValue('D4', 'วันที่สร้าง')
        ->setCellValue('E4', 'รูป');
        $i = 6; // กำหนดค่า i เป็น 6 เพื่อเริ่มพิมพ์ที่แถวที่ 6
    
        // Write data from MySQL result
        foreach(asset::find()->all() as $item){ //วนลูปหาพนักงานทั้งหมด
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $item["type"]);
         //กำหนดให้คอลัมม์ A แถวที่ i พิมพ์ค่าของ employeeNumber
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $item["number"]);
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, $item["name"]);
            $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, $item["create_date"]);
            $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, $item["image"]);
            $i++;
        }
    
        // Rename sheet
        //$objPHPExcel->getActiveSheet()->setTitle('Employees');
    
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        //$objPHPExcel->setActiveSheetIndex(0);
    
        // Save Excel 2007 file
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('myData.xlsx'); // Save File เป็นชื่อ myData.xlsx
        echo Html::a('ดาวน์โหลดเอกสาร', Url::to(Yii::getAlias('@web').'/myData.xlsx'), ['class' => 'btn btn-info']);  //สร้าง link download
    
    }


    public function actionIndex()
    {
        $searchModel = new AssetSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Asset model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Asset model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Asset();

        if ($model->load(Yii::$app->request->post())) {
            
            $model->image = UploadedFile::getInstance($model, 'image');

            $image_name = $model->image.rand(1, 4000).','.$model->image->extension;

            $image_path = 'uploads/image/'.$image_name;

            $model->image->saveAs($image_path);

            $model->image = $image_path;

            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Asset model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Asset model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Asset model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Asset the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Asset::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
