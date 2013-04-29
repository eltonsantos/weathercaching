#!/usr/bin/env python
#

import pyqrcode

qr_image = pyqrcode.MakeQRImage("http://www.example")
qr_image.show()
