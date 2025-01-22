window.get_advertisement = function get_advertisement(disp_cnt, type) {
    return new Promise((resolve, reject) => {
        $.ajax({
            type: "get",
            url: getAdvertisementUrl,
            headers: {
                //'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                //'Authorization': 'Bearer ' + apiToken
            },
            data: { disp_cnt: disp_cnt, search_type: type },
        })
        .done(data => {
            if (data && data.length > 0) {
                resolve(data);  // 成功時はresolveで結果を返す
            } else {
                resolve(null);  // データがない場合でもresolve
            }
        })
        .fail((xhr, status, error) => {
            console.error('Error fetching advertisement:', error);
            reject(error);  // 失敗時はrejectでエラーを返す
        });
    });
};

window.adv_click = function adv_click(adv_id) {
    sessionStorage.setItem('adv_disp_time', new Date().getTime());      //現在の時刻をセッションストレージに保存
    $.ajax({
        //POST通信
        type: "post",
        url: AdvertisementClickUrl,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: { adv_id: adv_id},
    })
    .done(response => {
    })
    .always(() => {
    })
    .fail((xhr, status, error) => {
    });

};