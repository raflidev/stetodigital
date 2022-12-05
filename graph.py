#!/usr/bin/env-python

import warnings
import json
import wave
import sys
import numpy as np
np.set_printoptions(threshold=sys.maxsize, suppress=True)
warnings.simplefilter("ignore", DeprecationWarning)

dt_audio = sys.argv[1]

spf = wave.open(dt_audio, 'r')
signal = spf.readframes(-1)
signal = np.fromstring(signal, dtype='int16')
fs = spf.getframerate()
Time = np.linspace(0, len(signal) / fs, num=len(signal))

path_dir = {}
path_dir['y'] = f' {signal}'
path_dir['x'] = f' {Time}'
print(json.dumps(path_dir, separators=(',', ':')))
