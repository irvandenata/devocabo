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
  <div class="snap-madatory snap-center container min-h-screen mx-auto justify-center items-center">


    <div class="w-full">
      <form class="static mt-4" id="explore" action="{{ route('group-words.index') }}" action="GET">
        <div class="relative z-1">
          <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
            <svg class="w-4 h-4 text-gray dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
              fill="none" viewBox="0 0 20 20">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
            </svg>
          </div>
          <input type="text" id="default-search" name="search"
            class="block w-full p-4 ps-10 text-sm text-gray-900 border-2 border-gray  rounded-lg bg-gray-50  focus:border-primary focus:border-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white focus:outline-primary"
            autocomplete="off" placeholder="Cari kelompok kata..." required>
          <button type="submit" id="btn-explore"
            class="text-black absolute end-2.5 bottom-2.5 bg-background hover:bg-blue-800 focus:border-2 focus:border-primary focus:outline-none  font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 ">Search</button>
        </div>
      </form>
    </div>
    <div class="mt-10">
      <h1 class="text-xl font-extrabold">Kelompok Kata Saya</h1>
      <button data-modal-target="static-modal" data-modal-toggle="static-modal"
        class="py-2 px-6 mt-4 rounded hover:border-primary border-2 border-background hover:border-2 bg-background border-1 outline-1 max-w-[300px] text-black text-center
        "
        onclick="showModal('static-modal','new')">Buat Kelompok Baru</button>
    </div>
    @if(request()->search)
    <div class="my-4">
        Hasil Pencarian : {{ request()->search }}
    </div>
    @endif
    <div class="grid lg:grid-cols-4 sm:grid-cols-1 gap-y-10 gap-x-6 mt-10 ">
      @foreach ($groupWords as $item)
        <div
          class="max-w-sm bg-background border-2 border-gray rounded-lg hover:border-primary shadow dark:bg-gray-800 dark:border-gray-700 ">
          <div class="">
            <img class="rounded-lg p-1"
              style="
            object-fit: cover;
            width: 100%;
            height: 200px;
            "
              src="{{ $item->image ? asset('storage/' . $item->image) : 'https://picsum.photos/seed/picsum/200/300' }}"
              alt="Article" />
          </div>
          <div class="p-5 ">
            <div class="">
              <h5 class="mb-1 text-xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $item->name }}</h5>

            </div>
            <p>{{ $item->words->count() }} Kata</p>
            <small>Akses : {{ $item->access == 1 ? 'Semua Orang' : 'Saya Sendiri' }}</small>
            <div class="relative">
              <small class="p-1 cursor-pointer rounded bg-primary text-white btn-description">Lihat Deskripsi</small>

              <div class="absolute hidden top-6 left-0 w-full bg-background rounded-lg p-3 border-gray border-2 ">
                <small class="text-gray-500">{{ $item->description }}</small>
              </div>
            </div>

            {{-- <p class="mb-3 font-thin text-sm text-gray-700 dark:text-gray-400">
              {!! $item->body !!}
            </p> --}}
            <div onclick="
            window.location.href = '{{ route('words.show', $item->slug) }}'
            " class="p-2 border-2 my-2 border-primary text-center text-sm rounded">
                Mode Menghafal
            </div>
            <div class="mt-2">
              <a href="{{ route('words.index', $item->slug) }}"
                class="inline-flex items-center p-2 text-sm font-medium bg-gray  text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 hover:bg-primary hover:text-black">
                Detail
              </a>
              <div onclick="editGroup('{{ $item->id }}')"
                class="inline-flex items-center p-2 text-sm font-medium bg-gray  text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 hover:bg-blue cursor-pointer hover:text-white">
                Edit
              </div>
              <div onclick="deleteGroup('{{ $item->id }}')"
                class="inline-flex items-center p-2 text-sm font-medium bg-gray  text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 hover:bg-orange hover:text-white cursor-pointer
                      ">
                Hapus
              </div>
            </div>
          </div>

        </div>
      @endforeach
    </div>
    @if ($groupWords->count() < 1)
      <div class="flex flex-col items-center justify-center">
        <h1 class="text-2xl font-bold text-center text-gray-900 dark:text-white">Kamu tidak memiliki kelompok kata besti,
          Silahkan Buat atau Clone dari yang teman-teman lain share ya :)</h1>
      </div>
    @endif

    <div class="w-full mt-10 mb-10 flex justify-center" id="pagination">
      {{ $groupWords->links() }}
      {{-- <nav aria-label="Page navigation example">
        <ul class="inline-flex -space-x-px text-base h-10">
          <li>
            <a href="#"
              class="flex items-center justify-center px-4 h-10 ms-0 leading-tight text-gray-500 bg-white border border-e-0 border-gray-300 rounded-s-lg hover:bg-background hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Previous</a>
          </li>
          <li>
            <a href="#"
              class="flex items-center  justify-center px-4 h-10 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-background hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">1</a>
          </li>
          <li>
            <a href="#"
              class="flex items-center justify-center px-4 h-10 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-background hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">2</a>
          </li>
          <li>
            <a href="#" aria-current="page"
              class="flex items-center justify-center px-4 h-10 hover:bg-background text-blue-600 border border-gray-300 bg-blue-50 hover:bg-blue-100 hover:text-blue-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white">3</a>
          </li>
          <li>
            <a href="#"
              class="flex items-center justify-center px-4 h-10 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-background hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">4</a>
          </li>
          <li>
            <a href="#"
              class="flex items-center justify-center px-4 h-10 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-background hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">5</a>
          </li>
          <li>
            <a href="#"
              class="flex items-center justify-center px-4 h-10 leading-tight text-gray-500 bg-white border border-gray-300 rounded-e-lg hover:bg-background hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Next</a>
          </li>
        </ul>
      </nav> --}}
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
          <h3 class="text-xl title-form font-semibold text-gray-900 dark:text-white">
            Ini Form Tambah Kelompok Kata ya . . .
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
          <form id="store-group">
            <div class="form-group">
              <label for="">Nama</label>
              <input type="text"
                class="w-full
                        border-2 border-gray rounded-lg
                        p-2 my-3
                        "
                id="name" placeholder="Isi Nama Kelompok Kata . . .">
            </div>
            <div class="form-group">
              <label for="">Deskripsi</label>
              <textarea type="text"
                class="w-full
                        border-2 border-gray rounded-lg
                        p-2 my-2
                        "
                id="deskripsi" placeholder="Deskripsikan kegunaan Kelompok ini . . ."></textarea>
            </div>
            <div class="form-group">
              <label for="">Akses</label>
              <select name="" id="akses"
                class="w-full
                        border-2 border-gray rounded-lg
                        p-2 my-2
                        ">
                <option value="" selected disabled>- Pilih Dulu</option>
                <option value="1">Semua Orang</option>
                <option value="0">Saya Sendiri</option>
              </select>
            </div>
            <div class="form-group">
              <label for="">Cover</label>
              <input type="file" accept="image/*"
                class="w-full
                          border-2 border-gray rounded-lg
                          p-2 my-3
                          "
                id="cover">
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
  <div id="edit-modal" tabindex="-1" aria-hidden="true"
    class="modal hidden  overflow-y-auto overflow-x-hidden bg-gray-dark bg-opacity-10 z-[100001] fixed top-0 right-0 left-0 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full mx-auto">
    <div class="relative p-4 w-full h-screen mx-auto  max-w-2xl max-h-full">
      <!-- Modal content -->
      <div class="relative bg-white rounded-lg shadow dark:bg-gray-700" style="transform: translateY(30%)">
        <!-- Modal header -->
        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
          <h3 class="text-xl title-form font-semibold text-gray-900 dark:text-white">
            Ini Form Ubah Kelompok Kata ya . . .
          </h3>
          <button type="button"
            class=" close-button text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
            data-modal-hide="default-modal">
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
              viewBox="0 0 14 14">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
            </svg>
            <span class="sr-only">Close modal</span>
          </button>
        </div>
        <!-- Modal body -->
        <div class="p-4 md:p-5 space-y-4">
          <form id="store-group">
            <input type="hidden" id="edit-id">
            <div class="form-group">
              <label for="">Nama</label>
              <input type="text"
                class="w-full
                      border-2 border-gray rounded-lg
                      p-2 my-3
                      "
                id="edit-name" placeholder="Isi Nama Kelompok Kata . . .">
            </div>
            <div class="form-group">
              <label for="">Deskripsi</label>
              <textarea type="text"
                class="w-full
                      border-2 border-gray rounded-lg
                      p-2 my-2
                      "
                id="edit-deskripsi" placeholder="Deskripsikan kegunaan Kelompok ini . . ."></textarea>
            </div>
            <div class="form-group">
              <label for="">Akses</label>
              <select name="" id="edit-akses"
                class="w-full
                      border-2 border-gray rounded-lg
                      p-2 my-2
                      ">
                <option value="" selected disabled>- Pilih Dulu</option>
                <option value="1">Semua Orang</option>
                <option value="0">Saya Sendiri</option>
              </select>
            </div>
            <div class="form-group">
              <label for="">Cover</label>
              <input type="file" accept="image/*"
                class="w-full
                        border-2 border-gray rounded-lg
                        p-2 my-3
                        "
                id="edit-cover">
            </div>
          </form>
        </div>
        <!-- Modal footer -->
        <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
          <button type="submit" onclick="onUpdate(event)"
            class="text-white bg-primary hover:bg-blue-800 border-2 hover:border-2 hover:border-gray  font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Simpan</button>
          <button data-modal-hide="default-modal" type="button"
            class="ms-3 close-button text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Batal</button>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('script')
  <script>
    function onStore(e) {
      //check filed not null

      let nama = document.getElementById('name').value;
      let deskripsi = document.getElementById('deskripsi').value;
      let fileCover = document.getElementById('cover').files[0];
      let akses = document.getElementById('akses').value;
      if (nama == '') {
        let elErr = document.getElementById('name');
        elErr.focus();
        toastr.error('Nama Kelompok Kata Tidak Boleh Kosong', 'Warning', {
          "progressBar": true,
          "positionClass": "toast-top-right",
          "preventDuplicates": true,
          "showDuration": "300",
          "hideDuration": "1000",
          "timeOut": "5000",
        });
        return;
      }
      if (akses == '') {
        let elErr = document.getElementById('akses');
        elErr.focus();
        toastr.error('Akses Kelompok Kata Tidak Boleh Kosong', 'Warning', {
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
      data.append('name', nama);
      data.append('description', deskripsi);
      data.append('cover', fileCover);
      data.append('access', akses);
      fetch("{{ route('group-words.store') }}", {
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
            toastr.success('Berhasil Menyimpan Kelompok Kata', 'Success', {
              "progressBar": true,
              "positionClass": "toast-top-right",
              "preventDuplicates": true,
              "showDuration": "300",
              "hideDuration": "1000",
              "timeOut": "5000",
            })
            document.getElementsByClassName('close-button')[0].click();
            setTimeout(() => {

              window.location.reload();
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

    function deleteGroup(id) {
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
          fetch("{{ route('group-words.destroy', '') }}/" + id, {
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
                  'Kelompok Kata Berhasil Dihapus.',
                  'success'
                )
                setTimeout(() => {

                  window.location.reload();
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
      })
    }

    function editGroup(id){
        fetch("/group-words/"+id+"/edit",{
            method: 'GET',
            headers: {
              'X-CSRF-TOKEN': "{{ csrf_token() }}",
              'Accept': 'application/json',
            },
          }).then(res => res.json())
          .then(res => {
            if (res){
              let modal = document.getElementById('edit-modal');
                modal.classList.remove('hidden');
                document.getElementById('edit-id').value = res.id;
                document.getElementById('edit-name').value = res.name;
                document.getElementById('edit-deskripsi').value = res.description;
                document.getElementById('edit-akses').value = res.access;
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
    $('form').submit(function(e) {
      e.preventDefault();
    });
    function onUpdate(e) {
      //check filed not null
      let id = document.getElementById('edit-id').value;
      let nama = document.getElementById('edit-name').value;
      let deskripsi = document.getElementById('edit-deskripsi').value;
      let fileCover = document.getElementById('edit-cover').files[0];
      let akses = document.getElementById('edit-akses').value;
      if (nama == '') {
        let elErr = document.getElementById('edit-name');
        elErr.focus();
        toastr.error('Nama Kelompok Kata Tidak Boleh Kosong', 'Warning', {
          "progressBar": true,
          "positionClass": "toast-top-right",
          "preventDuplicates": true,
          "showDuration": "300",
          "hideDuration": "1000",
          "timeOut": "5000",
        });
        return;
      }
      if (akses == '') {
        let elErr = document.getElementById('edit-akses');
        elErr.focus();
        toastr.error('Akses Kelompok Kata Tidak Boleh Kosong', 'Warning', {
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
      data.append('name', nama);
      data.append('description', deskripsi);
      data.append('cover', fileCover);
      data.append('access', akses);
      fetch("/group-words/"+id+"/update", {
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
            toastr.success('Berhasil Menyimpan Kelompok Kata', 'Success', {
              "progressBar": true,
              "positionClass": "toast-top-right",
              "preventDuplicates": true,
              "showDuration": "300",
              "hideDuration": "1000",
              "timeOut": "5000",
            })
            document.getElementsByClassName('close-button')[0].click();
            setTimeout(() => {

              window.location.reload();
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

    document.addEventListener('DOMContentLoaded', function() {
      let pagination = document.getElementById('pagination');
      let nav = pagination.getElementsByTagName('nav');
      //first child remove all class
      if (nav[0] != undefined) {
        nav[0].firstElementChild.classList.remove('flex', 'justify-between', 'flex-1', 'sm:hidden');
        nav[0].firstElementChild.remove();
        let caption = pagination.querySelector('p.leading-5');
        caption.remove();
        let pageBtn = nav[0].querySelector('.relative').children;
        //where atribute aria-current = page
        let activeBtn = nav[0].querySelector('span[aria-current="page"]').firstElementChild;
        //remove class
        activeBtn.classList.remove('border-gray-300', 'bg-white');
        //add class
        activeBtn.classList.add('bg-background');

        //foreach pageBtn
        console.log(pageBtn);
        for (let i = 0; i < pageBtn.length; i++) {
          pageBtn[i].classList.add('hover:bg-primary');
        }
        let firstBtn = pageBtn[0];
        let lastBtn = pageBtn[pageBtn.length - 1];
      }



      btnDescription = document.getElementsByClassName('btn-description');
      for (let i = 0; i < btnDescription.length; i++) {
        btnDescription[i].addEventListener('click', function() {
          this.nextElementSibling.classList.toggle('hidden');
          for (let j = 0; j < btnDescription.length; j++) {
            if (btnDescription[j] != this) {
              btnDescription[j].nextElementSibling.classList.add('hidden');
            }
          }
        })

      }
      $('*').click(function(e) {
        if (!$(e.target).is('.btn-description')) {
          $('.btn-description').next().addClass('hidden');
        }
      });

      btnExplore = document.getElementById('btn-explore');
        btnExplore.addEventListener('click', function() {
            let search = document.getElementById('default-search').value;
            if (search == '') {
            toastr.error('Kata Kunci Tidak Boleh Kosong', 'Warning', {
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": true,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
            });
            return;
            }
            let form = document.getElementById('explore');
            form.submit();
        })
    });
  </script>
@endpush
