wx.ready(function () {
    // ��������Ȧ
    wx.onMenuShareTimeline({
        title: wstitle,
        link: wslink,
        imgUrl: wsimg,
        success: function () {
            alert('����ɹ�');
        },
        cancel: function () {
        }
    });

    // ���������
    wx.onMenuShareAppMessage({
        title: wstitle,
        desc: wsdesc,
        link: wslink,
        imgUrl: wsimg,
        success: function () {
          alert('����ɹ�');
        },
        cancel: function () {
        }
    });

    // ����QQ
    wx.onMenuShareQQ({
        title: wstitle,
        desc: wsdesc,
        link: wslink,
        imgUrl: wsimg,
        success: function () {
            alert('����ɹ�');
        },
        cancel: function () {
        }
    });

    // ΢�ŵ���Ѷ΢��
    wx.onMenuShareWeibo({
        title: wstitle,
        desc: wsdesc,
        link: wslink,
        imgUrl: wsimg,
        success: function () {
            alert('����ɹ�');
        },
        cancel: function () {
        }
    });

    // ����QQ�ռ�
    wx.onMenuShareQZone({
        title: wstitle,
        desc: wsdesc,
        link: wslink,
        imgUrl: wsimg,
        success: function () {
            alert('����ɹ�');
        },
        cancel: function () {
        }
    });

});