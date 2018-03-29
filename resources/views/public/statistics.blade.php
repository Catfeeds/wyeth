<script>
var _hmt = _hmt || [];
    (function() {
      var hm = document.createElement("script");
      hm.src = "//hm.baidu.com/hm.js?48d0daf26b11c052fb2a98dcb072f1bc";
      var s = document.getElementsByTagName("script")[0];
      s.parentNode.insertBefore(hm, s);
    })();
</script>
<script type="text/javascript">
    @if(isset($uid))
        CIData.push(["setUserId", {{$uid}}]);
    @else
    @endif
    @if(isset($user_channel))
        CIData.push(["setChannel", "{{ $user_channel }}"]);
    @else
    @endif
    @if(isset($user_properties))
        var user_properties = <?php echo json_encode($user_properties, JSON_UNESCAPED_UNICODE); ?>;
        CIData.push(["setUserProperties", user_properties]);
    @else
    @endif
    CIData.push(['actionTimeStart', 'visit', {url: window.location.pathname}]);

    @if(isset($channel))
        var wyeth_channel = "{{ $channel }}";
    @else
        var wyeth_channel = '';
    @endif
</script>
<script async src="https://oneitfarm.com/cidata/main.php/json/script"></script>
