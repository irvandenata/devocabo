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
      <form class="static mt-4" id="explore" action="{{ route('group-words.explore') }}" action="GET">
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
            autocomplete="off" placeholder="Cari kelompok kata yang orang lain share ..." required>
          <button type="submit" id="btn-explore"
            class="text-black absolute end-2.5 bottom-2.5 bg-background hover:bg-blue-800 focus:border-2 focus:border-primary focus:outline-none  font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 ">Search</button>
        </div>
      </form>
    </div>
    @if(request()->search)
    <div class="mt-10">
      <h1 class="text-sm font-extrabold">Hasil Pencarian : {{ request()->search }}</h1>
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
            <small>Owner : {{ $item->user->name }}</small><br>
            <small>Jumlah Clone : {{ $item->clone }}</small>
            <div class="relative">
              <small class="p-1 cursor-pointer rounded bg-primary text-white btn-description">Lihat Deskripsi</small>

              <div class="absolute hidden top-6 left-0 w-full bg-background rounded-lg p-3 border-gray border-2 ">
                <small class="text-gray-500">{{ $item->description }}</small>
              </div>
            </div>

            {{-- <p class="mb-3 font-thin text-sm text-gray-700 dark:text-gray-400">
              {!! $item->body !!}
            </p> --}}
            <div class="mt-2">
              <a href="{{ route('words.index', $item->slug) }}"
                class="inline-flex items-center p-2 text-sm font-medium bg-gray  text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 hover:bg-primary hover:text-black">
                Detail
              </a>
              <div onclick="cloneGroup('{{ $item->id }}')"
                class="inline-flex items-center p-2 text-sm font-medium bg-gray  text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 hover:bg-green cursor-pointer hover:text-white">
                Clone
              </div>
            </div>
          </div>

        </div>
      @endforeach
    </div>
    @if ($groupWords->count() < 1)
      <div class="flex flex-col items-center justify-center">
        <h1 class="text-xl font-bold text-center text-gray-900 dark:text-white">
          Waduh . . . <br> Kelompok Kata yang kamu cari tidak ditemukan
        </h1>
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

    function editGroup(id) {
      fetch("/group-words/" + id + "/edit", {
          method: 'GET',
          headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}",
            'Accept': 'application/json',
          },
        }).then(res => res.json())
        .then(res => {
          if (res) {
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
      fetch("/group-words/" + id + "/update", {
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
