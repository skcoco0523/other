<div id="adv_modal" class="notification-overlay">
    <div class="notification-modal" onclick="event.stopPropagation()">
        <div class="modal-content">
            <div class="modal-header">
                    <h5 class="modal-title" id="newPlaylistModalLabel">広告</h5>
                    <button id="closeButton" type="button" class="btn-close" aria-label="Close" onclick="closeModal('adv_modal')" style="display: none;"></button>
                    <div id="countdown"></div>  <!--カウントダウンを表示-->
            </div>
            <div class="modal-body">
                <div id="popup-adv-items">
                    <!-- JavaScriptで動的に挿入される -->
                </div>
                
            </div>
        </div>
    </div>
</div>

<script>
    
    document.addEventListener('DOMContentLoaded', async function() {
         // 広告表示時間　(秒)   広告を強制表示する秒数
        let advDisplayTime = 5;           
        //ページ遷移時に再度広告が表示されるまでの間隔
        const disp_hour = 3;
        const advDisplayInterval = 60*60*disp_hour;
        let disp_flag = 0;
        // セッションストレージから前回の表示時刻を取得
        //const lastAdTime = sessionStorage.getItem('adv_disp_time');
        const lastAdTime = getCookie('adv_disp_time');

        const currentTime = new Date().getTime();
        if (lastAdTime) {
            const elapsedTime = (currentTime - lastAdTime) / 1000;  // ミリ秒を秒に変換
            // 指定した時間が経過していればモーダルを表示
            if (elapsedTime > advDisplayInterval) {
                disp_flag = 1;
            }
        } else {
            disp_flag = 1;
        }

        //ここで広告を表示する
        if(disp_flag == 1){

            //一旦複数件取得し、ランダムで1つを表示
            const advertisement = await get_advertisement(10,"popup");
            console.log(advertisement);        

            //表示対象の広告情報(popup)があった場合のみ表示
            if (advertisement && advertisement.length > 0) {
                const select_index = Math.floor(Math.random() * advertisement.length);  //ランダムで1つ選択
                const select_data = advertisement[select_index];
                const popupAdvlInner = document.getElementById('popup-adv-items');
                popupAdvlInner.innerHTML = ''; // 既存のスライドをクリア

                const item = `
                        <a href="${select_data.href}" rel="nofollow" onclick="adv_click(${select_data.id})">
                            <img src="${select_data.src}" class="d-block w-100">
                            <img border="0" width="1" height="1" src="${select_data.tracking_src}">
                        </a>
                `;
                popupAdvlInner.insertAdjacentHTML('beforeend', item);
                openModal('adv_modal');
            }
            
            //閉じるボタンの使用を制限
            const closeButton = document.getElementById('closeButton');
            const countdownDisplay  = document.getElementById('countdown');

            countdownDisplay .innerHTML = advDisplayTime;

            const interval = setInterval(function() {
                advDisplayTime--;
                countdownDisplay .innerHTML = advDisplayTime;

                if (advDisplayTime === 0) {
                    clearInterval(interval);
                    countdownDisplay.style.display = 'none'; // ボタンを表示
                    closeButton.style.display = 'block'; // ボタンを表示      
                    //指定秒数表示後、現在の時刻をクッキーに1週間保存
                    //sessionStorage.setItem('adv_disp_time', new Date().getTime());
                    setCookie("adv_disp_time", new Date().getTime(), 7, "/");
                }
            }, 1000); // 1秒ごとにカウントダウン
        }
    });

</script>

