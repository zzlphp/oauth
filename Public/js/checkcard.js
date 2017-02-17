//判断二代身份证的有效性

function checkcard(card) {
    var res = 1;
    cardlength = getCardLength(card);
    if(cardlength!=18){
        res = 0;
        return res;
    }
    var sc=new Array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
    sum = 0;
    for(i = 17; i > 0; i--){
        s= (Math.pow(2,i)) % 11;
        sum += s * card[17-i];
    }
    ress = sc[sum % 11];
    if(card[17] != ress){
        res = 0;
    }
    //年月日
    var year    = (card[6]+card[7]+card[8]+card[9])*1;
    var month   = (card[10]+card[11])*1;
    var date    = (card[12]+card[13])*1;

    var timeMachine=new Date();
    timeMachine.setFullYear(year);
  timeMachine.setMonth(month-1);//记住,月份是从0开始计数的
  timeMachine.setDate(date);
  var trueYear = timeMachine.getFullYear();
  var trueMonth = timeMachine.getMonth()+1;//记住,月份是从0开始计数的
  var trueDate = timeMachine.getDate();
 //javascript中的Date类型可以自动调整不正确的日期，比如2月31号，如果调整，则说明原输入日期不是正确的日期
  if (trueYear != year || trueMonth != month || trueDate != date) {
    res = 0;
  }
  return res;
}

function getCardLength(str)
{
    var realLength = 0, len = str.length, charCode = -1;
    for (var i = 0; i < len; i++) {
        charCode = str.charCodeAt(i);
        if (charCode >= 0 && charCode <= 128) realLength += 1;
        else realLength += 2;
    }
  return realLength;
}
//判断银行卡号
function luhmCheck(bankno)
{
    var lastNum=bankno.substr(bankno.length-1,1);//取出最后一位（与luhm进行比较）
 
    var first15Num=bankno.substr(0,bankno.length-1);//前15或18位
    var newArr=new Array();
    for(var i=first15Num.length-1;i>-1;i--){    //前15或18位倒序存进数组
        newArr.push(first15Num.substr(i,1));
    }
    var arrJiShu=new Array();  //奇数位*2的积 <9
    var arrJiShu2=new Array(); //奇数位*2的积 >9
     
    var arrOuShu=new Array();  //偶数位数组
    for(var j=0;j<newArr.length;j++){
        if((j+1)%2==1){//奇数位
            if(parseInt(newArr[j])*2<9)
            arrJiShu.push(parseInt(newArr[j])*2);
            else
            arrJiShu2.push(parseInt(newArr[j])*2);
        }
        else //偶数位
        arrOuShu.push(newArr[j]);
    }
     
    var jishu_child1=new Array();//奇数位*2 >9 的分割之后的数组个位数
    var jishu_child2=new Array();//奇数位*2 >9 的分割之后的数组十位数
    for(var h=0;h<arrJiShu2.length;h++){
        jishu_child1.push(parseInt(arrJiShu2[h])%10);
        jishu_child2.push(parseInt(arrJiShu2[h])/10);
    }        
     
    var sumJiShu=0; //奇数位*2 < 9 的数组之和
    var sumOuShu=0; //偶数位数组之和
    var sumJiShuChild1=0; //奇数位*2 >9 的分割之后的数组个位数之和
    var sumJiShuChild2=0; //奇数位*2 >9 的分割之后的数组十位数之和
    var sumTotal=0;
    for(var m=0;m<arrJiShu.length;m++){
        sumJiShu=sumJiShu+parseInt(arrJiShu[m]);
    }
     
    for(var n=0;n<arrOuShu.length;n++){
        sumOuShu=sumOuShu+parseInt(arrOuShu[n]);
    }
     
    for(var p=0;p<jishu_child1.length;p++){
        sumJiShuChild1=sumJiShuChild1+parseInt(jishu_child1[p]);
        sumJiShuChild2=sumJiShuChild2+parseInt(jishu_child2[p]);
    }      
    //计算总和
    sumTotal=parseInt(sumJiShu)+parseInt(sumOuShu)+parseInt(sumJiShuChild1)+parseInt(sumJiShuChild2);
     
    //计算Luhm值
    var k= parseInt(sumTotal)%10==0?10:parseInt(sumTotal)%10;        
    var luhm= 10-k;
     
    if(lastNum==luhm){
      return true;
    }
    else{
      return false;
    }        
}