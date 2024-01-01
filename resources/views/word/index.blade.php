@extends('layouts.app')

@push('meta-tag')
  <meta charset="UTF-8">
  <meta name="description" content="Cari Materi Bahasa ingris yang kamu inginkan">
  <meta name="keywords" content="Cari Materi, adalah, Belajar Bahasa, Pengertian, bagaimana , mudah,">
  <meta name="author" content="Irvan Denata">
  <meta name=”robots” content="index, follow">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
@endpush
@push('style')
  <style>
  </style>
@endpush
@section('content')
  <div class="snap-madatory snap-center container min-h-[75vh] mx-auto justify-center items-center">
    <div class="">
      <div>
        <!-- Breadcrumb -->
        <nav
          class="flex px-5 py-3 text-gray-700 border border-gray-200 rounded-lg bg-background dark:bg-gray-800 dark:border-gray-700"
          aria-label="Breadcrumb">
          <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
            <li class="inline-flex items-center">
              <a href="/"
                class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-primary dark:text-gray-400 dark:hover:text-white">
                <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                  viewBox="0 0 20 20">
                  <path
                    d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
                </svg>
                Kelompok Kata
              </a>
            </li>
            <li>
              <div class="flex items-center">
                <svg class="rtl:rotate-180 block w-3 h-3 mx-1 text-gray-400 " aria-hidden="true"
                  xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="m1 9 4-4-4-4" />
                </svg>
                <a href="#"
                  class="ms-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ms-2 dark:text-gray-400 dark:hover:text-white">{{ $group->name }}</a>
              </div>
            </li>
            {{-- <li aria-current="page">
              <div class="flex items-center">
                <svg class="rtl:rotate-180  w-3 h-3 mx-1 text-gray-400" aria-hidden="true"
                  xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="m1 9 4-4-4-4" />
                </svg>
                <span
                  class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">{{ $article->category->name }}</span>
              </div>
            </li> --}}
          </ol>
        </nav>
      </div>
      @if($group->user_id == Auth::id())
      <button data-modal-target="static-modal" data-modal-toggle="static-modal"
        class="py-2 px-6 mt-4 rounded hover:border-primary border-2 border-background hover:border-2 bg-background border-1 outline-1 max-w-[300px] text-black text-center
        "
        onclick="showModal('static-modal','new')">Tambah Kata Baru</button>
        @endif

        @if($group->user_id != Auth::id())
        <button data-modal-target="static-modal" data-modal-toggle="static-modal"
          class="py-2 px-6 mt-4 rounded hover:border-primary border-2 border-background hover:border-2 bg-background border-1 outline-1 max-w-[300px] text-black text-center
          "
          onclick="cloneGroup('{{ $group->id }}')">Clone Kelompok</button>
          @endif
        <button
        class="py-2 px-6 mt-4 rounded hover:border-primary border-2 border-background hover:border-2 bg-background border-1 outline-1 max-w-[300px] text-black text-center"
        onclick="refreshTable()"
        >Reload Data</button>
    </div>
    <div class="table-responsive mt-6">
      <table id="datatable" style="max-width:100% !important" class="table m-t-30">
        <thead>
          <tr>
            <th width="10%">No</th>
            <th width="20%">Action</th>
            @foreach ($rows['name'] as $item)
              <th>{{ $item }}</th>
            @endforeach

          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div>


  <!-- Main modal -->
  <div id="static-modal" tabindex="-1" aria-hidden="true"
    class="modal hidden  overflow-y-auto overflow-x-hidden bg-gray-dark bg-opacity-10 z-[100001] fixed top-0 right-0 left-0 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full mx-auto">
    <div class="relative p-4 w-full h-screen mx-auto  max-w-2xl max-h-full">
      <!-- Modal content -->
      <div class="relative bg-white rounded-lg shadow dark:bg-gray-700" style="transform: translateY(30%)">
        <!-- Modal header -->
        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
          <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
            Ini Form Tambah Kata
          </h3>
          <button type="button"
            class=" close-button text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
            data-modal-hide="default-modal">
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
            </svg>
            <span class="sr-only">Close modal</span>
          </button>
        </div>
        <!-- Modal body -->
        <div class="p-4 md:p-5 space-y-4">
          <form id="store-group" onsubmit="
            event.preventDefault();
            ">
            <small>Note : Tambah Kata dalam bahasa Indonesia atau bahasa Inggris</small>
            <div class="form-group mt-2">
              <label for="">Kata</label>
              <input type="text"
                class="w-full
                        border-2 border-gray rounded-lg
                        p-2 my-3
                        "
                id="word" placeholder="Isi Kata disini ya . . .">
            </div>
          </form>
        </div>
        <!-- Modal footer -->
        <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
          <button type="submit" onclick="onStore(event)"
            class="text-white bg-primary hover:bg-blue-800 border-2 hover:border-2 hover:border-gray  font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Simpan</button>
          <button data-modal-hide="default-modal" type="button"
            class="ms-3 close-button text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Batal</button>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('script')
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
  <script>
    let datatable = $('#datatable').DataTable({
      processing: true,
      serverSide: true,
      ajax: {
        url: "{{ route('words.index', $groupId) }}",
        type: 'GET',
        data: function(d) {
          d.search = $('#default-search').val();
        }
      },
      columns: [{
          data: 'DT_RowIndex',
          name: 'DT_RowIndex',
          orderable: false,
          searchable: false,
          className: 'text-center'

        },
        {
          data: 'action',
          name: 'action',
          orderable: false,
          searchable: false,
          className: 'text-center'

        },
        @foreach ($rows['column'] as $item)
          {
            data: '{{ $item }}',
            name: '{{ $item }}',
            className: 'text-center'
          },
        @endforeach
      ]
    });

    function refreshTable() {
      datatable.ajax.reload();
    }

    function onStore(e) {
      //check filed not null

      let word = document.getElementById('word').value;
      if (word == '') {
        let elErr = document.getElementById('word');
        elErr.focus();
        toastr.error('Kata Tidak Boleh Kosong', 'Warning', {
          "progressBar": true,
          "positionClass": "toast-top-right",
          "preventDuplicates": true,
          "showDuration": "300",
          "hideDuration": "1000",
          "timeOut": "5000",
        });
        return;
      }
      var data = new FormData();
      data.append('word', word);
      data.append('group_word_id', '{{ $group->id }}');
      fetch("{{ route('words.store', $groupId) }}", {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}",
            'Accept': 'application/json',
          },
          body: data
        }).then(res => res.json())
        .then(res => {
          console.log(res);
          if (res.status == 200) {
            toastr.success('Berhasil Menyimpan Kata', 'Success', {
              "progressBar": true,
              "positionClass": "toast-top-right",
              "preventDuplicates": true,
              "showDuration": "300",
              "hideDuration": "1000",
              "timeOut": "5000",
            })
            document.getElementsByClassName('close-button')[0].click();
            refreshTable();
          } else {
            for (let i = 0; i < res.length; i++) {
              toastr.error(res[i], 'Warning', {
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": true,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
              })
            }
          }
        })
        .catch(function(err) {
            for (const key in err) {
              if (Object.hasOwnProperty.call(err, key)) {
                const element = err[key];
                toastr.error(element, 'Warning', {
                  "progressBar": true,
                  "positionClass": "toast-top-right",
                  "preventDuplicates": true,
                  "showDuration": "300",
                  "hideDuration": "1000",
                  "timeOut": "5000",
                })
              }
            }
          }

        )
    }

    function deleteItem(id) {
      Swal.fire({
        title: 'Apakah Kamu Yakin?',
        text: "Kamu tidak akan bisa mengembalikan data ini lagi!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',

        confirmButtonText: 'Ya, Hapus Saja!'
      }).then((result) => {
        if (result.isConfirmed) {
          fetch("/words/" + id, {
              method: 'DELETE',
              headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                'Accept': 'application/json',

              },
            }).then(res => res.json())
            .then(res => {
              console.log(res);
              if (res.status == 200) {
                Swal.fire(
                  'Terhapus!',
                  'Kata Berhasil Dihapus.',
                  'success'
                )
                refreshTable();
              } else {
                for (let i = 0; i < res.length; i++) {
                  toastr.error(res[i], 'Warning', {
                    "progressBar": true,
                    "positionClass": "toast-top-right",
                    "preventDuplicates": true,
                    "showDuration": "300",
                    "hideDuration": "1000",
                    "timeOut": "5000",
                  })
                }
              }
            })
            .catch(function(err) {
                for (const key in err) {
                  if (Object.hasOwnProperty.call(err, key)) {
                    const element = err[key];
                    toastr.error(element, 'Warning', {
                      "progressBar": true,
                      "positionClass": "toast-top-right",
                      "preventDuplicates": true,
                      "showDuration": "300",
                      "hideDuration": "1000",
                      "timeOut": "5000",
                    })
                  }
                }
              }

            )
        }
      })
    }

    function cloneGroup(id) {
      Swal.fire({
        title: 'Apakah Kamu Yakin?',
        text: "Kamu akan mengclone kelompok kata ini!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',

        confirmButtonText: 'Ya !'
      }).then((result) => {
        if (result.isConfirmed) {
          fetch("{{ route('group-words.clone', '') }}/" + id, {
              method: 'GET',
              headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                'Accept': 'application/json',
              },
            }).then(res => res.json())
            .then(res => {
              if (res.status == 200) {
                Swal.fire(
                  'Berhasil!',
                  'Kelompok Kata Berhasil DiClone.',
                  'success'
                )
                setTimeout(() => {
                  window.location.href = "{{ route('group-words.index') }}";
                }, 1000);
              } else {
                for (let i = 0; i < res.length; i++) {
                  toastr.error(res[i], 'Warning', {
                    "progressBar": true,
                    "positionClass": "toast-top-right",
                    "preventDuplicates": true,
                    "showDuration": "300",
                    "hideDuration": "1000",
                    "timeOut": "5000",
                  })
                }
              }
            })
            .catch(function(err) {
                for (const key in err) {
                  if (Object.hasOwnProperty.call(err, key)) {
                    const element = err[key];
                    toastr.error(element, 'Warning', {
                      "progressBar": true,
                      "positionClass": "toast-top-right",
                      "preventDuplicates": true,
                      "showDuration": "300",
                      "hideDuration": "1000",
                      "timeOut": "5000",
                    })
                  }
                }
              }

            )
        }
      });
    }

  </script>
@endpush
