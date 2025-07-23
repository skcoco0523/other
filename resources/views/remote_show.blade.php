@extends('layouts.app') {{-- あなたの基本レイアウトを継承 --}}

@section('content')
    <div class="container py-4">
        <h1 class="mb-4">スマートリモコン管理</h1>

        {{-- ================================================ --}}
        {{-- デバイスリストのアコーディオン --}}
        {{-- ================================================ --}}
        <div class="accordion" id="deviceListAccordion">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingDevices">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDevices" aria-expanded="false" aria-controls="collapseDevices">
                        <h3>デバイスリスト</h3>
                    </button>
                </h2>
                <div id="collapseDevices" class="accordion-collapse collapse" aria-labelledby="headingDevices" data-bs-parent="#deviceListAccordion">
                    <div class="accordion-body">

                        {{-- 新規デバイス登録行用のテーブル --}}
                        <table class="table table-borderless table-center" style="table-layout: fixed;">
                            <td class="col-2 icon-55 d-flex justify-content-center b-gray" onclick="openModal('add_iotdevice-modal');">
                                <i class="fa-solid fa-plus icon-25 red"></i>
                            </td>
                            <td class="col-10" onclick="openModal('add_iotdevice-modal');" style="vertical-align: middle;">
                                新規デバイス
                            </td>
                        </table>

                        {{-- 既存のデバイスリストのテーブル --}}
                        @include('layouts.list_table', ['iot_devices_table' => $iotdevice_list, 'table' => 'iotdevice'])

                        {{-- ページャー用のパディング --}}
                        <div class="p-3"> 
                            @php
                                $additionalParams = ['table' => 'iotdevice' ,];
                            @endphp
                            @include('layouts.pagination', ['paginator' => $iotdevice_list,'additionalParams' => $additionalParams,])
                        </div>

                    </div>
                </div>
            </div>
        </div>

        {{-- ================================================ --}}
        {{-- リモコンリストのアコーディオン --}}
        {{-- ================================================ --}}
        <div class="accordion" id="remoteListAccordion">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingRemotes">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseRemotes" aria-expanded="true" aria-controls="collapseRemotes">
                        <h3>リモコンリスト</h3>
                    </button>
                </h2>
                <div id="collapseRemotes" class="accordion-collapse collapse show" aria-labelledby="headingRemotes" data-bs-parent="#remoteListAccordion">
                    <div class="accordion-body">

                        {{-- 新規デバイス登録行用のテーブル --}}
                        <table class="table table-borderless table-center" style="table-layout: fixed;">
                            <td class="col-2 icon-55 d-flex justify-content-center b-gray" onclick="openModal('add_virtualremote-modal');">
                                <i class="fa-solid fa-plus icon-25 red"></i>
                            </td>
                            <td class="col-10" onclick="openModal('add_virtualremote-modal');" style="vertical-align: middle;">
                                新規リモコン
                            </td>
                        </table>

                        {{-- 既存のリモコンリストのテーブル --}}
                        @include('layouts.list_table', ['virtual_remote_table' => $virtual_remote_list, 'table' => 'iotdevice'])

                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- デバイス追加ポップアップモーダル -->
    @include('modals.add_iotdevice-modal')

    <!-- リモコン追加ポップアップモーダル -->
    @include('modals.add_virtualremote-modal')

    <?//広告モーダル?>   
    @include('layouts.adv_popup')
    

@endsection
