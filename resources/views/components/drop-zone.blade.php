@props(['action', 'name' => 'file', 'multiple' => false, 'accept' => '*/*', 'method' => 'POST', 'additionalData' => []])

<div 
    x-data="{
        isDropping: false,
        isUploading: false,
        progress: 0,
        errorMessage: '',
        successMessage: '',
        
        handleDrop(e) {
            this.isDropping = false;
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                this.uploadFiles(files);
            }
        },
        
        handleSelect(e) {
            const files = e.target.files;
            if (files.length > 0) {
                this.uploadFiles(files);
            }
        },
        
        uploadFiles(files) {
            this.isUploading = true;
            this.progress = 0;
            this.errorMessage = '';
            this.successMessage = '';
            
            const formData = new FormData();
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
            if ('{{ $method }}' !== 'POST') {
                formData.append('_method', '{{ $method }}');
            }

            const extraData = @json($additionalData);
            for (const key in extraData) {
                formData.append(key, extraData[key]);
            }
            
            @if($multiple)
            for (let i = 0; i < files.length; i++) {
                formData.append(`{{ $name }}[]`, files[i]);
            }
            @else
            formData.append('{{ $name }}', files[0]);
            @endif

            const xhr = new XMLHttpRequest();
            xhr.open('POST', '{{ $action }}', true);
            xhr.setRequestHeader('Accept', 'application/json');
            
            xhr.upload.onprogress = (event) => {
                if (event.lengthComputable) {
                    this.progress = Math.round((event.loaded / event.total) * 100);
                }
            };
            
            xhr.onload = () => {
                this.isUploading = false;
                if (xhr.status >= 200 && xhr.status < 300) {
                    this.successMessage = 'Uploaded successfully!';
                    $dispatch('upload-success', JSON.parse(xhr.responseText));
                    setTimeout(() => { this.successMessage = ''; this.progress = 0; }, 3000);
                } else {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        this.errorMessage = response.message || 'Upload failed.';
                    } catch (e) {
                        this.errorMessage = 'An error occurred during upload.';
                    }
                }
            };
            
            xhr.onerror = () => {
                this.isUploading = false;
                this.errorMessage = 'Network error during upload.';
            };
            
            xhr.send(formData);
        }
    }"
    class="w-full"
>
    <!-- Drop Zone Area -->
    <div 
        @dragover.prevent="isDropping = true" 
        @dragleave.prevent="isDropping = false" 
        @drop.prevent="handleDrop"
        @click="$refs.fileInput.click()"
        :class="isDropping ? 'border-primary bg-primary/5 scale-[1.02]' : 'border-gray-300 bg-gray-50 hover:bg-gray-100'"
        class="border-2 border-dashed rounded-xl p-8 flex flex-col justify-center items-center cursor-pointer transition-all duration-200"
    >
        <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
        <p class="text-gray-600 font-medium text-center">
            {{ __('Drag & drop your files here, or') }} <span class="text-primary">{{ __('browse') }}</span>
        </p>
        <p class="text-xs text-gray-500 mt-2 text-center">{{ __('Supported files: PDF, images, docs') }}</p>
        <input 
            type="file" 
            x-ref="fileInput" 
            class="hidden" 
            @change="handleSelect" 
            accept="{{ $accept }}" 
            {{ $multiple ? 'multiple' : '' }}
        >
    </div>

    <!-- Progress UI -->
    <div x-show="isUploading" x-transition class="mt-4 p-4 bg-white border border-gray-100 rounded-lg shadow-sm">
        <div class="flex justify-between items-center mb-2 text-sm">
            <span class="font-medium text-gray-700">{{ __('Uploading...') }}</span>
            <span class="text-gray-500 font-mono text-xs" x-text="`${progress}%`"></span>
        </div>
        <x-upload-progress progress="progress" />
    </div>

    <!-- Error/Success Feedback -->
    <div x-show="errorMessage" x-transition class="mt-3 text-sm text-red-600 font-medium" x-text="errorMessage"></div>
    <div x-show="successMessage" x-transition class="mt-3 text-sm text-green-600 font-medium" x-text="successMessage"></div>
</div>
