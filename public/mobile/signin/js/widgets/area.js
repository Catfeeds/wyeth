define(function(require, exports, module) {
  var win = window;
  var country= [["AO", "安哥拉"],   ["AF", "阿富汗"],   ["AL", "阿尔巴尼亚"],   ["DZ", "阿尔及利亚"],   ["AD", "安道尔共和国"],   ["AI", "安圭拉岛"],   ["AG", "安提瓜和巴布达"],   ["AR", "阿根廷"],   ["AM", "亚美尼亚"],   ["AU", "澳大利亚"],   ["AT", "奥地利"],   ["AZ", "阿塞拜疆"],   ["BS", "巴哈马"],  ["BH", "巴林"],   ["BD", "孟加拉国"],   ["BB", "巴巴多斯"],   ["BY", "白俄罗斯"],   ["BE", "比利时"],   ["BZ", "伯利兹"],   ["BJ", "贝宁"],   ["BM", "百慕大群岛"],   ["BO", "玻利维亚"],   ["BW", "博茨瓦纳"],   ["BR", "巴西"],   ["BN", "文莱"],   ["BG", "保加利亚"],   ["BF", "布基纳法索"],   ["MM", "缅甸"],   ["BI", "布隆迪"],   ["CM", "喀麦隆"],   ["CA", "加拿大"],  ["CF", "中非共和国"],   ["TD", "乍得"],   ["CL", "智利"],   ["CN", "中国"],   ["CO", "哥伦比亚"],   ["CG", "刚果"],   ["CK", "库克群岛"],   ["CR", "哥斯达黎加"],  ["CU", "古巴"],   ["CY", "塞浦路斯"],   ["CZ", "捷克"],   ["DK", "丹麦"],   ["DJ", "吉布提"],   ["DO", "多米尼加共和国"],   ["EC", "厄瓜多尔"],   ["EG", "埃及"],   ["SV", "萨尔瓦多"],   ["EE", "爱沙尼亚"],   ["ET", "埃塞俄比亚"],   ["FJ", "斐济"],   ["FI", "芬兰"],   ["FR", "法国"],   ["GF", "法属圭亚那"],   ["GA", "加蓬"],  ["GM", "冈比亚"],   ["GE", "格鲁吉亚"],   ["DE", "德国"],   ["GH", "加纳"],   ["GI", "直布罗陀"],   ["GR", "希腊"],   ["GD", "格林纳达"],   ["GU", "关岛"],   ["GT", "危地马拉"],   ["GN", "几内亚"],  ["GY", "圭亚那"],   ["HT", "海地"],   ["HN", "洪都拉斯"],   ["HK", "香港"],   ["HU", "匈牙利"],   ["IS", "冰岛"],   ["IN", "印度"],   ["ID", "印度尼西亚"],   ["IR", "伊朗"],   ["IQ", "伊拉克"],   ["IE", "爱尔兰"],   ["IL", "以色列"],   ["IT", "意大利"],   ["JM", "牙买加"],["JP", "日本"], ["JO", "约旦"],["KH", "柬埔寨"],["KZ", "哈萨克斯坦"],["KE", "肯尼亚"],["KR", "韩国"],["KW", "科威特"],["KG", "吉尔吉斯坦"],["LA", "老挝"],["LV", "拉脱维亚"],["LB", "黎巴嫩"],["LS", "莱索托"],["LR", "利比里亚"], ["LY", "利比亚"],["LI", "列支敦士登"],["LT", "立陶宛"],["LU", "卢森堡"],["MO", "澳门"],["MG", "马达加斯加"],["MW", "马拉维"],["MY", "马来西亚"],["MV", "马尔代夫"],["ML", "马里"],["MT", "马耳他"],["MU", "毛里求斯"],["MX", "墨西哥"], ["MD", "摩尔多瓦"], ["MC", "摩纳哥"], ["MN", "蒙古"], ["MS", "蒙特塞拉特岛"],["MA", "摩洛哥"],["MZ", "莫桑比克"],["NA", "纳米比亚"],["NR", "瑙鲁"], ["NP", "尼泊尔"],["NL", "荷兰"],["NZ", "新西兰"],["NI", "尼加拉瓜"],["NE", "尼日尔"], ["NG", "尼日利亚"], ["KP", "朝鲜"],["NO", "挪威"],["OM", "阿曼"],["PK", "巴基斯坦"],["PA", "巴拿马"], ["PG", "巴布亚新几内亚"], ["PY", "巴拉圭"],["PE", "秘鲁"], ["PH", "菲律宾"], ["PL", "波兰"], ["PF", "法属玻利尼西亚"], ["PT", "葡萄牙"], ["PR", "波多黎各"], ["QA", "卡塔尔"], ["RO", "罗马尼亚"], ["RU", "俄罗斯"], ["LC", "圣卢西亚"],["VC", "圣文森特岛"],["SM", "圣马力诺"],["ST", "圣多美和普林西比"],["SA", "沙特阿拉伯"],["SN", "塞内加尔"], ["SC", "塞舌尔"], ["SL", "塞拉利昂"], ["SG", "新加坡"], ["SK", "斯洛伐克"], ["SI", "斯洛文尼亚"],["SB", "所罗门群岛"], ["SO", "索马里"], ["ZA", "南非"], ["ES", "西班牙"], ["LK", "斯里兰卡"], ["SD", "苏丹"], ["SR", "苏里南"], ["SZ", "斯威士兰"], ["SE", "瑞典"], ["CH", "瑞士"], ["SY", "叙利亚"],["TJ", "塔吉克斯坦"],["TZ", "坦桑尼亚"],["TH", "泰国"],["TG", "多哥"],["TO", "汤加"],["TT", "特立尼达和多巴哥"],["TN", "突尼斯"],["TR", "土耳其"],["TM", "土库曼斯坦"],["UG", "乌干达"],["UA", "乌克兰"],["AE", "阿拉伯联合酋长国"],["GB", "英国"],["US", "美国"],["UY", "乌拉圭"],["UZ", "乌兹别克斯坦"],["VE", "委内瑞拉"],["VN", "越南"],["YE", "也门"],["YU", "南斯拉夫"],["ZW", "津巴布韦"],["ZR", "扎伊尔"],["ZM", "赞比亚"]];
  var province = new Array("北京市","上海市","天津市","重庆市","河北省","山西省","内蒙古自治区","辽宁省","吉林省","黑龙江省","江苏省","浙江省","安徽省","福建省","江西省","山东省","河南省","湖北省","湖南省","广东省","广西壮族自治区","海南省","四川省","贵州省","云南省","西藏自治区","陕西省","甘肃省","宁夏回族自治区","青海省","新疆维吾尔族自治区","香港特别行政区","澳门特别行政区","台湾省");  
  var city = new Array();   
  city[0] = new Array("北京市","东城|西城|崇文|宣武|朝阳|丰台|石景山|海淀|门头沟|房山|通州|顺义|昌平|大兴|平谷|怀柔|密云|延庆");   
  city[1] = new Array("上海市","黄浦|卢湾|徐汇|长宁|静安|普陀|闸北|虹口|杨浦|闵行|宝山|嘉定|浦东|金山|松江|青浦|南汇|奉贤|崇明");   
  city[2] = new Array("天津市","和平|东丽|河东|西青|河西|津南|南开|北辰|河北|武清|红挢|塘沽|汉沽|大港|宁河|静海|宝坻|蓟县");   
  city[3] = new Array("重庆市","万州|涪陵|渝中|大渡口|江北|沙坪坝|九龙坡|南岸|北碚|万盛|双挢|渝北|巴南|黔江|长寿|綦江|潼南|铜梁 |大足|荣昌|壁山|梁平|城口|丰都|垫江|武隆|忠县|开县|云阳|奉节|巫山|巫溪|石柱|秀山|酉阳|彭水|江津|合川|永川|南川");   
  city[4] = new Array("河北省","石家庄|邯郸|邢台|保定|张家口|承德|廊坊|唐山|秦皇岛|沧州|衡水");   
  city[5] = new Array("山西省","太原|大同|阳泉|长治|晋城|朔州|吕梁|忻州|晋中|临汾|运城");   
  city[6] = new Array("内蒙古自治区","呼和浩特|包头|乌海|赤峰|呼伦贝尔盟|阿拉善盟|哲里木盟|兴安盟|乌兰察布盟|锡林郭勒盟|巴彦淖尔盟|伊克昭盟");   
  city[7] = new Array("辽宁省","沈阳|大连|鞍山|抚顺|本溪|丹东|锦州|营口|阜新|辽阳|盘锦|铁岭|朝阳|葫芦岛");   
  city[8] = new Array("吉林省","长春|吉林|四平|辽源|通化|白山|松原|白城|延边");   
  city[9] = new Array("黑龙江省","哈尔滨|齐齐哈尔|牡丹江|佳木斯|大庆|绥化|鹤岗|鸡西|黑河|双鸭山|伊春|七台河|大兴安岭");   
  city[10] = new Array("江苏省","南京|镇江|苏州|南通|扬州|盐城|徐州|连云港|常州|无锡|宿迁|泰州|淮安");   
  city[11] = new Array("浙江省","杭州|宁波|温州|嘉兴|湖州|绍兴|金华|衢州|舟山|台州|丽水");   
  city[12] = new Array("安徽省","合肥|芜湖|蚌埠|马鞍山|淮北|铜陵|安庆|黄山|滁州|宿州|池州|淮南|巢湖|阜阳|六安|宣城|亳州");   
  city[13] = new Array("福建省","福州|厦门|莆田|三明|泉州|漳州|南平|龙岩|宁德");   
  city[14] = new Array("江西省","南昌市|景德镇|九江|鹰潭|萍乡|新馀|赣州|吉安|宜春|抚州|上饶");   
  city[15] = new Array("山东省","济南|青岛|淄博|枣庄|东营|烟台|潍坊|济宁|泰安|威海|日照|莱芜|临沂|德州|聊城|滨州|菏泽");   
  city[16] = new Array("河南省","郑州|开封|洛阳|平顶山|安阳|鹤壁|新乡|焦作|濮阳|许昌|漯河|三门峡|南阳|商丘|信阳|周口|驻马店|济源");   
  city[17] = new Array("湖北省","武汉|宜昌|荆州|襄樊|黄石|荆门|黄冈|十堰|恩施|潜江|天门|仙桃|随州|咸宁|孝感|鄂州");  
  city[18] = new Array("湖南省","长沙|常德|株洲|湘潭|衡阳|岳阳|邵阳|益阳|娄底|怀化|郴州|永州|湘西|张家界");   
  city[19] = new Array("广东省","广州|深圳|珠海|汕头|东莞|中山|佛山|韶关|江门|湛江|茂名|肇庆|惠州|梅州|汕尾|河源|阳江|清远|潮州|揭阳|云浮");   
  city[20] = new Array("广西壮族自治区","南宁|柳州|桂林|梧州|北海|防城港|钦州|贵港|玉林|南宁地区|柳州地区|贺州|百色|河池");   
  city[21] = new Array("海南省","海口|三亚|儋州|五指山|文昌|琼海|万宁|东方");   
  city[22] = new Array("四川省","成都|绵阳|德阳|自贡|攀枝花|广元|内江|乐山|南充|宜宾|广安|达川|雅安|眉山|甘孜|凉山|泸州");   
  city[23] = new Array("贵州省","贵阳|六盘水|遵义|安顺|铜仁|黔西南|毕节|黔东南|黔南");   
  city[24] = new Array("云南省","昆明|大理|曲靖|玉溪|昭通|楚雄|红河|文山|思茅|西双版纳|保山|德宏|丽江|怒江|迪庆|临沧");  
  city[25] = new Array("西藏自治区","拉萨|日喀则|山南|林芝|昌都|阿里|那曲");   
  city[26] = new Array("陕西省","西安|宝鸡|咸阳|铜川|渭南|延安|榆林|汉中|安康|商洛");   
  city[27] = new Array("甘肃省","兰州|嘉峪关|金昌|白银|天水|酒泉|张掖|武威|定西|陇南|平凉|庆阳|临夏|甘南");   
  city[28] = new Array("宁夏回族自治区","银川|石嘴山|吴忠|固原");   
  city[29] = new Array("青海省","西宁|海东|海南|海北|黄南|玉树|果洛|海西");   
  city[30] = new Array("新疆维吾尔族自治区","乌鲁木齐|石河子|克拉玛依|伊犁|巴音郭勒|昌吉|克孜勒苏柯尔克孜|博尔塔拉|吐鲁番|哈密|喀什|和田|阿克苏");   
  city[31] = new Array("香港特别行政区","中西区|东区|南区|湾仔区|九龙区|观塘区|深水埗区|黄大仙区|油尖旺区|离岛区|葵青区|北区|西贡区|沙田区|大埔区|荃湾区|屯门区|元朗区");   
  city[32] = new Array("澳门特别行政区","花地玛堂区|圣安多尼堂区|大堂区|望德堂区|风顺堂区");   
  city[33] = new Array("台湾省","台北|高雄|台中|台南|屏东|南投|云林|新竹|彰化|苗栗|嘉义|花莲|桃园|宜兰|基隆|台东|金门|马祖|澎湖");   

  var $selectType  = $('#selectType'),
    $selectProvince  = $('#selectProvince'),
    $selectCity  = $('#selectCity'),
    $selectCountry  = $('#selectCountry');
  function addEvent(){

      $selectType.on('change',function(){
        var $this = $(this);
        if($this.val() == '中国'){
          $selectProvince.show();
          $selectCity.show();
          $selectCountry.hide();
        }else{
          $selectProvince.hide();
          $selectCity.hide();
          $selectCountry.show();
        }
      }) 
      $selectProvince.on('change',function(){
        var $this = $(this),
          val = $this.val();
        if(val == '请选择城市'){
          $selectCity.html('<option>请选择城市</option>');
          return;
        }else{
          for(var i=0;i<city.length;i++){
            var cityItem = city[i];
            if(cityItem[0]==val){
              var cityStr = '<option>请选择城市</option>';
              var cityItemList = cityItem[1].split("|");
              for(var j=0;j<cityItemList.length;j++){
                cityStr += '<option>'+cityItemList[j]+'</option>'
              }
              $selectCity.html(cityStr);
              break;
            }
          }
        }
      }) 
  }
    exports.init = function(data){
      addEvent();

      /* 初始化省份 */
      var listStr = '';
      for(var i=0; i<province.length;i++){
        listStr += '<option>'+province[i]+'</option>'
      }
      $selectProvince.append(listStr);
      /* 初始化国家 */
      var countryStr = '';
      for(var i=0; i<country.length;i++){
        countryStr += '<option>'+country[i][1]+'</option>'
      }
      $selectCountry.append(countryStr);

      if(data){
        if(data[0]=="中国"){
          var option1 = $selectProvince.find('option');
          /* 初始化城市 */
          for(var i=0;i<city.length;i++){
            var cityItem = city[i];
            if(cityItem[0]==data[1]){
              var cityStr = '<option>请选择城市</option>';
              var cityItemList = cityItem[1].split("|");
              for(var j=0;j<cityItemList.length;j++){
                if(cityItemList[j] == data[2]){
                  cityStr += '<option selected="true">'+cityItemList[j]+'</option>';
                }else{
                  cityStr += '<option>'+cityItemList[j]+'</option>';
                }
              }
              $selectCity.html(cityStr);
              break;
            }
          }
          for(var i=0; i<option1.length; i++){
            if(option1.eq(i).text() == data[1]){
              option1.eq(i).attr('selected',true);
              break;
            }
          }
        }else{
          $selectType.find('option').eq(1).attr('selected',true);
          $selectProvince.hide();
          $selectCity.hide();
          $selectCountry.show();
          var option2 = $selectCountry.find('option');
          for(var i=0; i<option2.length; i++){
            if(option2.eq(i).text() == data[1]){
              option2.eq(i).attr('selected',true);
              break;
            }
          }
        }
      }
    }
});




