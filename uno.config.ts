import { defineConfig, presetAttributify, presetIcons } from 'unocss'
import presetMini from '@unocss/preset-mini';
import presetPico from "unocss-preset-pico";

export default defineConfig({
    presets: [
        presetMini(),
        presetPico(),
        /*
        presetWind4({
            arbitraryVariants: false,
            preflights: {
                reset: false,
                theme: false,
                property: false,
            }
        }),
        */
        presetAttributify(),
        presetIcons(
            {

            }
        ),
    ],
    cli: {
        entry:
        [
            {
                outFile: 'public/build/assets/uno-static.css',
                patterns: ['public/**/*.html'],
                splitCss: 'multi'
            },
            {
                outFile: 'public/build/assets/uno.css',
                patterns: ['src/Page/**/*.{php,md}', 'src/{Components,Layout}/**/*.view.php'],
                splitCss: 'multi'
            }
        ]
    },
    content: {
        pipeline: {
            include: [
                // the default
                // include js/ts files
                'src/**/*.{php,md}',
            ],
            // exclude files
            // exclude: []
        },
    },
})