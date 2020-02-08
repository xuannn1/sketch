
export const bbcodTestCases = [
  {
    id: `blank`,
    test: ``,
  },
  {
    id: `newlines`,
    test: `multiple lines\nnew line\n\n\n3 blank lines\n`},
//   {
//     id: 'headers',
//     test: `[h1]h1[/h1]\n[h2]h2[/h2]\nnormal\n`,
//   }, Not supported
  {
    id: 'color',
    test: `[highlight=#b2b200][color=#0066cc]color[/color][/highlight]`,
  },
  {
    id: 'color2',
    test: `[color=#6b24b2]purple[/color][color=#e60000]red[/color][highlight=#b2b200][color=#e60000]WithYellowBG[/color][/highlight][highlight=#b2b200][color=#66a3e0]NOW_BLUE_TEXT[/color][/highlight]`,
  },
  {
    id: 'color_rainbow',
    test: `[highlight=#cce0f5]           [/highlight][highlight=#cce0f5][color=#e60000]I [/color][/highlight][highlight=#cce0f5][color=#ff9900]AM[/color][/highlight][highlight=#cce0f5][color=#ffff00] A[/color][/highlight][highlight=#cce0f5][color=#66b966] R[/color][/highlight][highlight=#cce0f5][color=#cce8cc]AI[/color][/highlight][highlight=#cce0f5][color=#0066cc]NB[/color][/highlight][highlight=#cce0f5][color=#9933ff]OW[/color][/highlight][highlight=#cce0f5][color=#ffffff]         [/color][/highlight]\n[highlight=#cce0f5][color=#ffffff]                                                 [/color][/highlight]\n[highlight=#facccc]Did you see a rainbow in sky? [/highlight]`,
  },
  {
    id: 'space',
    test: `[highlight=#cce0f5]                  space    around               [/highlight]\n[highlight=#cce0f5]                                                          [/highlight]`,
  },
];