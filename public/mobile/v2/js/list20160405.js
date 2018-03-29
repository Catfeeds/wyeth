AppList = {
    options: {},

    ui: {
        courseArea: $('#courseArea'),
        courseList: $('#courseList')
    },

    initLoadMore: function () {
        var self = this;
        this.dropload = this.ui.courseArea.dropload({
            scrollArea : window,
            domDown : {
                domClass   : 'dropload-down',
                domRefresh : '',
                domLoad    : '<div class="dropload-load"><span class="loading" hw_loading></span>加载中...</div>',
                domNoData  : ''
            },
            loadDownFn : function(me) {
                self.ajaxData();
            }
        });
    },

    ajaxData: function () {
        var self = this;
        $.ajax({
            type: 'GET',
            url: '/mobile/getDroploadData?' + $.param(self.options.dropParam),
            dataType: 'json',
            success: function(data){
                //$('[hw_loading]').remove();
                $('.dropload-down').remove();
                $('.dropload-load').remove();
                if(data.length >0){
                    var result = self.compileItem(data);
                    self.ui.courseList.append(result);
                    self.dropload.resetload();
                } else {
                    self.ui.courseList.append('<li class="dropload-load">全部加载完毕</li>');
                    self.dropload.lock();
                }
            },
            error: function(xhr, type){
                console.log('Ajax error!');
            }
        });
    },

    compileItem: function (data){
        var self = this;
        var result = '';
        for(var i = 0, l = data.length; i < l; i++) {
            result += '<li class="table-view-cell media" hw_item="' + data[i]['id'] + '">';
            result += '<a class="navigate-right" href="' + data[i]['url'] + '">';
            result += '<div class="pic-left"><img class="media-object pull-left" src="' + data[i]['img'] + '"></div>';
            result += '<div class="media-body">';
            result += '<div class="media-right">';

            if(data[i]['is_signed'] && data[i]['status'] != 3){
                result += '<i class="icon icon-status04"></i>';
            }else{
                result += '<i class="icon icon-status0' + data[i]['status'] + '"></i>';
            }

            result += '</div>';
            result += '<h3>' + data[i]['title'] + '</h3>';
            result += '<p class="item"><i class="icon icon-calendar"></i>' + data[i]['start_day'] + ' ' + data[i]['start_time'] + '</p>';
            result += '<p class="item"><i class="icon icon-user"></i>' + data[i]['teacher_name'] + ' ' + data[i]['teacher_hospital'] + '</p>';
            result += '<p class="item"><i class="icon icon-heart"></i>' + data[i]['hot'] + '</p>';
            result += '</div>';
            result += '</a>';
            /*
            if(self.options.dropParam.id < data[i]['id']){
                self.options.dropParam.id = data[i]['id'];
            }
            */
        }
        self.options.dropParam.page++;
        return result;
    },

    initSegmentedControl: function () {
        $('.segmented-control .control-item').on('click',function(){
            var $this = $(this);
            var $bg = $('.segmented-control .bg');
            $this.addClass('active').siblings('.control-item').removeClass('active');
            var idx = $this.index();
            if(idx==1){
                $bg.css({left:13,width:150});
            }else if(idx==2){
                $bg.css({left:183,width:150});
            }else if(idx==3){
                $bg.css({left:342,width:185});
        }
      });
    },

    // search
    initSearch: function () {
        var self = this;
        $('[hw_type]').on('click', function(e) {
            self.options.dropParam.type = $(e.currentTarget).attr('hw_type');
            //self.ui.courseList.html('<li class="dropload-load"><span class="loading" hw_loading></span>加载中 ....</li>');
            self.ui.courseList.html('');
            self.ui.courseArea.append('<li class="dropload-load"><span class="loading" hw_loading></span>加载中 ....</li>');
            self.options.dropParam.page = 1;
            self.dropload.unlock();
            self.ajaxData();
            return false;
        });
        $('[hw_stage]').on('click', function(e) {
            self.options.dropParam.stage = $(e.currentTarget).attr('hw_stage');
            $('[hw_stage]').removeClass('active')
            $(e.currentTarget).addClass('active');
            //self.ui.courseList.html('<li class="dropload-load"><span class="loading" hw_loading></span>加载中 ....</li>');
            self.ui.courseList.html('');
            self.ui.courseArea.append('<li class="dropload-load"><span class="loading" hw_loading></span>加载中 ....</li>');
            self.options.dropParam.page = 1;
            self.dropload.unlock();
            self.ajaxData();
            return false;
        });
        $('[hw_tag]').on('click', function(e) {
            self.options.dropParam.tag = $(e.currentTarget).attr('hw_tag');
            $('[hw_tag]').removeClass('active')
            $(e.currentTarget).addClass('active');
            //self.ui.courseList.html('<li class="dropload-load"><span class="loading" hw_loading></span>加载中 ....</li>');
            self.ui.courseList.html('');
            self.ui.courseArea.append('<li class="dropload-load"><span class="loading" hw_loading></span>加载中 ....</li>');
            self.options.dropParam.page = 1;
            self.dropload.unlock();
            self.ajaxData();
            return false;
        });
    },

    init: function (options) {
        this.options = options;
        this.options.dropParam = {
            //id: this.ui.courseList.children().last().attr('hw_item'),
            page: options.page,
            type: 'review',
            stage: '',
            tag: options.tag
        }

        this.initLoadMore();
        this.initSegmentedControl();
        this.initSearch();
    }
};