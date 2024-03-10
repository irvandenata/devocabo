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
    .active {
      background-color: black !important;
      color: white !important;

    }
  </style>
@endpush
@section('content')
  <div class="snap-madatory snap-center container min-h-[70vh] mx-auto justify-center items-center">
    <div class="text-center font-bold text-xl mb-2">
      {{ $group->name }}
    </div>
    <div onclick="
        window.location.href = '/';
        "
      class="p-3 border-2 border-gray rounded bg-background mb-3">Kembali</div>

    <div class="filter mb-3">
      <div class="group flex mt-3">
        <div class="relative group w-[40%]">
          <button id="dropdown-button"
            class="inline-flex justify-center w-full px-4 py-2 text-sm font-medium text-gray-700 bg-white border-2 border-gray rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-blue-500">
            <span class="mr-2" id="country-1" data-id="id">Indonesia</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ml-2 -mr-1" viewBox="0 0 20 20" fill="currentColor"
              aria-hidden="true">
              <path fill-rule="evenodd"
                d="M6.293 9.293a1 1 0 011.414 0L10 11.586l2.293-2.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                clip-rule="evenodd" />
            </svg>
          </button>
          <div id="dropdown-menu"
            class="hidden  absolute right-0 mt-2 z-50 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 p-1 space-y-1">
            <!-- Search input -->
            <input id="search-input"
              class="block w-full px-4 py-2 text-gray-800 border-2 rounded-md  border-gray focus:outline-none"
              type="text" placeholder="Cari Bahasa" autocomplete="off">
            <!-- Dropdown content goes here -->
            <div id="item-1">
            </div>
          </div>
        </div>
        <div class="w-[20%] text-center">
          <div class="p-3 text-sm">-Ke-</div>
        </div>
        <div class="relative group w-[40%]">
          <button id="dropdown-button-2"
            class="inline-flex justify-center w-full px-4 py-2 text-sm font-medium text-gray-700 bg-white border-2 border-gray rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-blue-500">
            <span class="mr-2" id="country-2" data-id="en">Inggris</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ml-2 -mr-1" viewBox="0 0 20 20" fill="currentColor"
              aria-hidden="true">
              <path fill-rule="evenodd"
                d="M6.293 9.293a1 1 0 011.414 0L10 11.586l2.293-2.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                clip-rule="evenodd" />
            </svg>
          </button>
          <div id="dropdown-menu-2"
            class="hidden absolute right-0 mt-2 z-50 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 p-1 space-y-1">
            <!-- Search input -->
            <input id="search-input-2"
              class="block w-full px-4 py-2 text-gray-800 border-2 rounded-md  border-gray focus:outline-none"
              type="text" placeholder="Cari Bahasa" autocomplete="off">
            <!-- Dropdown content goes here -->
            <div id="item-2">

            </div>
          </div>
        </div>
      </div>
      <div class="p-2 my-2 border-2 rounded border-gray text-center cursor-pointer hover:bg-black hover:text-white"
        onclick="reloadPage()">Terapkan</div>
    </div>
    <div class="my-2">Filter Kata :</div>
    <div class="my-4  grid grid-cols-4 gap-x-6">
      <div
        class="border-2 p-2 cursor-pointer hover:bg-background  border-gray text-center rounded @if (!isset(request()->type) || request()->type == -1) active @endif"
        onclick="filter(-1)">Semua</div>
      <div
        class="border-2 p-2 cursor-pointer hover:bg-background  border-gray text-center rounded @if (request()->type == 1) active @endif"
        onclick="filter(1)">Hafal</div>
      <div
        class="border-2 p-2 cursor-pointer hover:bg-background  border-gray text-center rounded @if (request()->type == 2) active @endif"
        onclick="filter(2)">Lupa</div>
      <div
        class="border-2 p-2 cursor-pointer hover:bg-background  border-gray text-center rounded @if (isset(request()->type) && request()->type == 0) active @endif"
        onclick="filter(0)">Belum</div>
    </div>
    <div id="controls-carousel" class="relative w-full" data-carousel="static">
      <!-- Carousel wrapper -->

      <div class="relative h-72 overflow-hidden md:h-96">
        @php
          $index = 0;
        @endphp
        @foreach ($words as $key => $item)
          <div id="slide-{{ $loop->iteration }}"
            class=" @if ($loop->iteration != 1) hidden @endif  sm:mb-[100px] slide duration-700 ease-in-out border-2 w-full h-full rounded border-gray"
            data-carousel-item>
            <div class="absolute right-[10px] top-[10px] z-[100] cursor-pointer " onclick="goTo()">
              {{ $loop->iteration }} / {{ $amount }}
            </div>
            <div class="absolute  text-3xl text-center font-bold"
              style="
          top: 50%;
            left: 50%;
          transform: translate(-50%, -50%);
          ">
              {{ $means[$key]['word'] }}</div>
            <div class="absolute meaning"
              style="
          top: 75%;
            left: 50%;
          transform: translate(-50%, -50%);
          ">
              {{ $means[$key]['mean'] }}
            </div>
            <div class="my-4 absolute  w-[90%] left-[50%] translate-x-[-50%]  grid grid-cols-3 gap-x-6 z-[1000]"
              style="bottom:0">

              <div id="done-{{ $loop->iteration }}"
                class="sm:text-[10px] btn-type-{{ $loop->iteration }} border-2 p-2 cursor-pointer hover:bg-background  border-gray text-center rounded @if ($item->type == 1) active @endif"
                onclick="changeType(event,'{{ $item['word'] }}',1)">Hafal</div>
              <div id="undone-{{ $loop->iteration }}"
                class="sm:text-[10px] btn-type-{{ $loop->iteration }} border-2 p-2 cursor-pointer hover:bg-background  border-gray text-center rounded @if ($item->type == 2) active @endif"
                onclick="changeType(event,'{{ $item['word'] }}',2)">Lupa</div>
              <div id="not-{{ $loop->iteration }}"
                class="sm:text-[10px] btn-type-{{ $loop->iteration }} border-2 p-2 cursor-pointer hover:bg-background  border-gray text-center rounded @if ($item->type == 0) active @endif"
                onclick="changeType(event,'{{ $item['word'] }}',0)">Belum</div>
            </div>
          </div>
          @php
            $index++;
          @endphp
        @endforeach

      </div>
      <!-- Slider controls -->
      <div
        class="absolute top-0 start-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
        id='prev'>
        <span
          class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
          <svg class="w-4 h-4 text-gray dark:text-gray-800 rtl:rotate-180" aria-hidden="true"
            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M5 1 1 5l4 4" />
          </svg>
          <span class="sr-only">Previous</span>
        </span>
      </div>
      <div type="button"
        class="absolute top-0 end-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
        id='next'>
        <span
          class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
          <svg class="w-4 h-4 text-gray dark:text-gray-800 rtl:rotate-180" aria-hidden="true"
            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="m1 9 4-4-4-4" />
          </svg>
          <span class="sr-only">Next</span>
        </span>
      </div>
    </div>
    <div id="btn-mean"
      class="p-3 border-2 cursor-pointer hover:bg-black hover:text-white border-gray rounded my-3 text-center"
      onclick="changeShowMean()">
      Sembunyikan Arti
    </div>
  </div>
@endsection

@push('script')
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
  <script>
    let showMean = true;
    let slide = 1;
    let totalSlide = {{ $amount }};
    let prevBtn = document.getElementById('prev');
    let nextBtn = document.getElementById('next');
    let carousel = document.getElementById('controls-carousel');
    nextBtn.addEventListener('click', function() {
      slide++;
      if (slide > totalSlide) {
        slide = 1;
      }
      let slides = document.getElementsByClassName('slide')

      for (let i = 0; i < slides.length; i++) {
        slides[i].classList.add('hidden');
      }
      document.getElementById('slide-' + slide).classList.remove('hidden');
    });
    prevBtn.addEventListener('click', function() {
      slide--;
      if (slide < 1) {
        slide = totalSlide;
      }
      let slides = document.getElementsByClassName('slide')
      for (let i = 0; i < slides.length; i++) {
        slides[i].classList.add('hidden');
      }
      document.getElementById('slide-' + slide).classList.remove('hidden');
    });

    let daftarNegara = [{
        'nama': 'Indonesia',
        'kode': 'id',
      },
      {
        'nama': 'Inggris',
        'kode': 'en',
      },
      {
        'nama': 'Arab',
        'kode': 'ar',
      },
      {
        'nama': 'Jepang',
        'kode': 'ja',
      }

    ];

    // fetch('https://restcountries.com/v3.1/all')
    //   .then(response => response.json())
    //   .then(data => {
    //     daftarNegara = {

    //     }
    //   })
    //   .catch(error => console.error('Error:', error));
    const dropdownButton = document.getElementById('dropdown-button');
    const dropdownMenu = document.getElementById('dropdown-menu');
    const searchInput = document.getElementById('search-input');
    let isOpen = false; // Set to true to open the dropdown by default

    // Function to toggle the dropdown state
    function toggleDropdown() {
      isOpen = !isOpen;
      dropdownMenu.classList.toggle('hidden', !isOpen);
    }

    // Set initial state

    dropdownButton.addEventListener('click', () => {
      toggleDropdown();
    });

    // Add event listener to filter items based on input
    searchInput.addEventListener('input', () => {
      const searchTerm = searchInput.value.toLowerCase();
      document.getElementById('item-1').innerHTML = '';
      if (searchTerm.length < 3) {
        return;
      }
      results = daftarNegara.filter(country => {
        return country.nama.toLowerCase().includes(searchTerm);
      });
      for (let i = 0; i < results.length; i++) {
        document.getElementById('item-1').innerHTML +=
          `<a href="#" onclick="chooseCountry('${results[i].nama}','${results[i].kode}')" class="block country px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">${results[i].nama}</a>`;
      }
    });

    function chooseCountry(name, kode) {
      let country = document.getElementById('country-1');
      country.innerHTML = name;
      country.attributes['data-id'].value = kode;
      searchInput.value = '';
      document.getElementById('item-1').innerHTML = '';

      toggleDropdown();
    }
    const dropdownButton2 = document.getElementById('dropdown-button-2');
    const dropdownMenu2 = document.getElementById('dropdown-menu-2');
    const searchInput2 = document.getElementById('search-input-2');
    let isOpen2 = false; // Set to true to open the dropdown by default

    // Function to toggle the dropdown state
    function toggleDropdown2() {
      isOpen2 = !isOpen2;
      let btnDropdown = document.getElementById('dropdown-menu-2');
      if (isOpen2) {
        btnDropdown.classList.remove('hidden');
      } else {
        btnDropdown.classList.add('hidden');
      }
    }

    // Set initial state

    dropdownButton2.addEventListener('click', () => {
      toggleDropdown2();
    });

    // Add event listener to filter items based on input
    searchInput2.addEventListener('input', () => {
      const searchTerm = searchInput2.value.toLowerCase();
      document.getElementById('item-2').innerHTML = '';
      if (searchTerm.length < 3) {
        return;
      }
      results = daftarNegara.filter(country => {
        return country.nama.toLowerCase().includes(searchTerm);
      });
      for (let i = 0; i < results.length; i++) {
        document.getElementById('item-2').innerHTML +=
          `<a href="#" onclick="chooseCountry2('${results[i].nama}','${results[i].kode}')" class="block country px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">${results[i].nama}</a>`;
      }
    });

    function chooseCountry2(name, kode) {
      document.getElementById('country-2').innerHTML = name;
      document.getElementById('country-2').attributes['data-id'].value = kode;
      searchInput2.value = '';
      document.getElementById('item-2').innerHTML = '';

      toggleDropdown2();
    }

    // on tap outside dropdown
    document.addEventListener('click', function(event) {
      var isClickInside = dropdownButton.contains(event.target);
      var isClickInside2 = dropdownButton2.contains(event.target);
      var isClickInside3 = dropdownMenu.contains(event.target);
      var isClickInside4 = dropdownMenu2.contains(event.target);
      if (!isClickInside && !isClickInside2 && !isClickInside3 && !isClickInside4) {
        dropdownMenu.classList.add('hidden');
        dropdownMenu2.classList.add('hidden');
        isOpen = false;
        isOpen2 = false;
      }
    });
    let country1 = "{{ request()->country1 ? request()->country1 : 'id' }}";
    let country2 = "{{ request()->country2 ? request()->country2 : 'en' }}";

    function reloadPage() {
      country1 = document.getElementById('country-1').attributes['data-id'].value;
      country2 = document.getElementById('country-2').attributes['data-id'].value;
      window.location.href = `{{ route('words.show', $group->slug) }}?country1=${country1}&country2=${country2}`;
    }

    @if (request()->country1)
      document.getElementById('country-1').attributes['data-id'].value = '{{ request()->country1 }}';
      document.getElementById('country-1').innerHTML = daftarNegara.filter(country => {
        return country.kode == '{{ request()->country1 }}';
      })[0].nama;
    @endif
    @if (request()->country2)
      document.getElementById('country-2').attributes['data-id'].value = '{{ request()->country2 }}';
      document.getElementById('country-2').innerHTML = daftarNegara.filter(country => {
        return country.kode == '{{ request()->country2 }}';
      })[0].nama;
    @endif

    function changeShowMean() {
      let meanings = document.getElementsByClassName('meaning');
      let btnMean = document.getElementById('btn-mean');
      for (let i = 0; i < meanings.length; i++) {
        if (showMean) {
          meanings[i].classList.add('hidden');
          btnMean.innerHTML = 'Tampil Arti';
        } else {
          meanings[i].classList.remove('hidden');
          btnMean.innerHTML = 'Sembunyikan Arti';
        }
      }
      showMean = !showMean;
    }

    function filter(type) {
      window.location.href =
        `{{ route('words.show', $group->slug) }}?country1=${country1}&country2=${country2}&type=${type}`;
    }

    function changeType(el, word, type) {
      let oldType = "{{ request()->type ?? -1 }}"
      let btnTypes = document.getElementsByClassName('btn-type-' + el.target.id.split('-')[1]);
      for (let i = 0; i < btnTypes.length; i++) {
        btnTypes[i].classList.remove('active');
      }
      el.target.classList.add('active');
      fetch("/words-change/" + word + '/{{ $group->id }}/en', {
          method: 'PUT',
          headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}",
            'Accept': 'application/json',
            'Content-Type': 'application/json'

          },
          body: JSON.stringify({
            type: type,
            old_type: oldType
          })
        }).then(response => response.json())
        .then(data => {
          if (data.status == 200) {
            toastr.success(data.message);
          } else {

          }

        }).catch(error => toastr.error('Error:', error));


    }

    function goTo() {
      Swal.fire({
        title: 'Masukan nomor slide',
        input: 'number',
        inputAttributes: {
          autocapitalize: 'off'
        },
        showCancelButton: true,
        confirmButtonText: 'Go',
        showLoaderOnConfirm: true,
        preConfirm: (slide) => {
          if (slide > 0 && slide <= {{ $amount }}) {
            let slides = document.getElementsByClassName('slide')
            for (let i = 0; i < slides.length; i++) {
              slides[i].classList.add('hidden');
            }
            document.getElementById('slide-' + slide).classList.remove('hidden');

          } else {
            Swal.showValidationMessage(
              `Nomor slide tidak valid`
            )
          }
        },
        allowOutsideClick: () => !Swal.isLoading()
      })

    }
  </script>
@endpush
