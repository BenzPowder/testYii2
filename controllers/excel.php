//การออกรายงาน excel จะเรียกใช้ Package 2 ตัวดังนี้
// Microsoft Excel
 use PHPExcel;
 use PHPExcel_IOFactory;
//สร้างAction ดังนี้
public function actionExcel() {
     // Create new PHPExcel object
     $objPHPExcel = new PHPExcel(); //สร้างไฟล์ excel
     // Add some data
     $objPHPExcel->setActiveSheetIndex(0)
     ->setCellValue('A2', 'Employees Report') //กำหนดให้ cell A2 พิมพ์คำว่า Employees Report
     ->setCellValue('A4', 'employeeNumber') //กำหนดให้ cell A4 พิมพ์คำว่า employeeNumber
     ->setCellValue('B4', 'firstName') //กำหนดให้ cell B4 พิมพ์คำว่า firstName
     ->setCellValue('C4', 'lastName') //กำหนดให้ cell C4 พิมพ์คำว่า lastName
     ->setCellValue('D4', 'extension') //กำหนดให้ cell D4 พิมพ์คำว่า extension
     ->setCellValue('E4', 'email') //กำหนดให้ cell E4 พิมพ์คำว่า email
     ->setCellValue('F4', 'officeCode') //กำหนดให้ cell D4 พิมพ์คำว่า officeCode
     ->setCellValue('G4', 'reportsTo') //กำหนดให้ cell G4 พิมพ์คำว่า reportsTo
     ->setCellValue('H4', 'jobTitle'); //กำหนดให้ cell H4 พิมพ์คำว่า jobTitle
     $i = 6; // กำหนดค่า i เป็น 6 เพื่อเริ่มพิมพ์ที่แถวที่ 6
 
     // Write data from MySQL result
     foreach(Employees::find()->all() as $item){ //วนลูปหาพนักงานทั้งหมด
         $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $item["employeeNumber"]);
      //กำหนดให้คอลัมม์ A แถวที่ i พิมพ์ค่าของ employeeNumber
         $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $item["firstName"]);
         $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, $item["lastName"]);
         $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, $item["extension"]);
         $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, $item["email"]);
         $model = Offices::findOne($item["officeCode"]);
        //query หาชื่อจังหวัดที่มีค่าตรงกับ officeCode ของพนักงาน
         $objPHPExcel->getActiveSheet()->setCellValue('F' . $i, $model->city); 
         // แทนค่าคอลัมม์ที่ F แถวที่  i ด้วย City ที่ query ออกมาได้
         $objPHPExcel->getActiveSheet()->setCellValue('G' . $i, $item["reportsTo"]);
         $objPHPExcel->getActiveSheet()->setCellValue('H' . $i, $item["jobTitle"]);
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