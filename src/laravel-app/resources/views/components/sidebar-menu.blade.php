<div class="overflow-y-scroll h-screen scrollbar-thin scrollbar-thumb-blue-500 scrollbar-track-gray-200">
    <div class="mt-5 flex h-fit w-full items-center gap-3 px-8">
        <div
            class="flex h-12 w-12 flex-shrink-0 items-center justify-center overflow-hidden rounded-full bg-blue-500 font-medium text-white"
        >
            <img
                class="h-full w-full object-cover"
                src="https://tuk-cdn.s3.amazonaws.com/assets/components/avatars/a_3_7.png"
                alt="{{ 'Dr. Shahadev Kumar Adhikari' }}"
            />
        </div>
        <div>
            <h2 class="text-xs font-bold text-indigo-500 md:text-sm">Dr. Shahadev</h2>
            <p class="text-xs text-[10px] font-bold">Dermatologist</p>
        </div>
    </div>
    <ul class="py-6">
        <a href="{{ route('dashboard') }}">
            <li @class(getMenuStyles('dashboard',request()->route()->getName(),))>
                <div class="flex items-center">
                    <div>
                        <i class="fa-solid fa-chart-line text-base sm:text-lg md:text-xl"></i>
                    </div>
                    <span class="ml-2">Dashboard</span>
                </div>
            </li>
        </a>

        <a href="{{ route('dashboard.illnesses') }}">
            <li @class(getMenuStyles('dashboard.illnesses',request()->route()->getName(),))>
                <div class="flex items-center">
                    <div>
                        <i class="fa-solid fa-disease text-base sm:text-lg md:text-xl"></i>
                    </div>
                    <span class="ml-2">Illnesses</span>
                </div>
            </li>
        </a>

        <a href="{{ route('dashboard.complaints') }}">
            <li @class(getMenuStyles('dashboard.complaints',request()->route()->getName(),))>
                <div class="flex items-center">
                    <div>
                        <i class="fa-solid fa-comment-medical text-base sm:text-lg md:text-xl"></i>
                    </div>
                    <span class="ml-2">Complaints</span>
                </div>
            </li>
        </a>

        <a href="{{ route('dashboard.examinations') }}">
            <li @class(getMenuStyles('dashboard.examinations',request()->route()->getName(),))>
                <div class="flex items-center">
                    <div>
                        <i class="fa-solid fa-flask-vial text-base sm:text-lg md:text-xl"></i>
                    </div>
                    <span class="ml-2">Examinations</span>
                </div>
            </li>
        </a>

        <a href="{{ route('dashboard.investigations') }}">
            <li @class(getMenuStyles('dashboard.investigations',request()->route()->getName(),))>
                <div class="flex items-center">
                    <div>
                        <i class="fa-solid fa-bugs text-base sm:text-lg md:text-xl"></i>
                    </div>
                    <span class="ml-2">Investigations</span>
                </div>
            </li>
        </a>

        <a href="{{ route('dashboard.medicines') }}">
            <li @class(getMenuStyles('dashboard.medicines',request()->route()->getName(),))>
                <div class="flex items-center">
                    <div>
                        <i class="fa-solid fa-capsules text-base sm:text-lg md:text-xl"></i>
                    </div>
                    <span class="ml-2">Medicines</span>
                </div>
            </li>
        </a>

        <a href="{{ route('dashboard.menstrual-diseases') }}">
            <li @class(getMenuStyles('dashboard.menstrual-diseases',request()->route()->getName(),))>
                <div class="flex items-center">
                    <div>
                        <i class="fa-solid fa-calendar-days text-base sm:text-lg md:text-xl"></i>
                    </div>
                    <span class="ml-2">Menstrual Diseases</span>
                </div>
            </li>
        </a>

        <a href="{{ route('dashboard.patients') }}">
            <li @class(getMenuStyles('dashboard.patients',request()->route()->getName(),))>
                <div class="flex items-center">
                    <div>
                        <i class="fa-solid fa-bed text-base sm:text-lg md:text-xl"></i>
                    </div>
                    <span class="ml-2">Patients</span>
                </div>
            </li>
        </a>

        <a href="{{ route('dashboard.special-procedures') }}">
            <li @class(getMenuStyles('dashboard.special-procedures',request()->route()->getName(),))>
                <div class="flex items-center">
                    <div>
                        <i class="fa-solid fa-hand-sparkles text-base sm:text-lg md:text-xl"></i>
                    </div>
                    <span class="ml-2">Special Procedures</span>
                </div>
            </li>
        </a>

        <a href="{{ route('dashboard.associated-sites') }}">
            <li @class(getMenuStyles('dashboard.associated-sites',request()->route()->getName(),))>
                <div class="flex items-center">
                    <div>
                        <i class="fa-solid fa-sitemap text-base sm:text-lg md:text-xl"></i>
                    </div>
                    <span class="ml-2">Associated sites</span>
                </div>
            </li>
        </a>

        <a href="{{ route('dashboard.personal-infos') }}">
            <li @class(getMenuStyles('dashboard.personal-infos',request()->route()->getName(),))>
                <div class="flex items-center">
                    <div>
                        <i class="fa-solid fa-circle-question text-base sm:text-lg md:text-xl"></i>
                    </div>
                    <span class="ml-2">Personal Infos</span>
                </div>
            </li>
        </a>

        <a href="{{ route('dashboard.concomitant-diseases') }}">
            <li @class(getMenuStyles('dashboard.concomitant-diseases',request()->route()->getName(),))>
                <div class="flex items-center">
                    <div>
                        <i class="fa-solid fa-square-virus text-base sm:text-lg md:text-xl"></i>
                    </div>
                    <span class="ml-2">Diseases</span>
                </div>
            </li>
        </a>

        <a href="{{ route('dashboard.clinics') }}">
            <li @class(getMenuStyles('dashboard.clinics',request()->route()->getName(),))>
                <div class="flex items-center">
                    <div>
                        <i class="fas fa-clinic-medical text-base sm:text-lg md:text-xl"></i>
                    </div>
                    <span class="ml-2">Clinics</span>
                </div>
            </li>
        </a>

        <a href="{{ route('dashboard.vaccinations') }}">
            <li @class(getMenuStyles('dashboard.vaccinations',request()->route()->getName(),))>
                <div class="flex items-center">
                    <div>
                        <i class="fas fa-syringe text-base sm:text-lg md:text-xl"></i>
                    </div>
                    <span class="ml-2">Vaccinations</span>
                </div>
            </li>
        </a>

        <a href="{{ route('dashboard.obstetrics') }}">
            <li @class(getMenuStyles('dashboard.obstetrics',request()->route()->getName(),))>
                <div class="flex items-center">
                    <div>
                        <i class="fas fa-person-pregnant text-base sm:text-lg md:text-xl"></i>
                    </div>
                    <span class="ml-2">Obstetrics</span>
                </div>
            </li>
        </a>

        <a href="{{ route('dashboard.operations') }}">
            <li @class(getMenuStyles('dashboard.operations',request()->route()->getName(),))>
                <div class="flex items-center">
                    <div>
                        <i class="fas fa-hospital-user text-base sm:text-lg md:text-xl"></i>
                    </div>
                    <span class="ml-2">Operations</span>
                </div>
            </li>
        </a>
    </ul>
    </ul>
</div>
