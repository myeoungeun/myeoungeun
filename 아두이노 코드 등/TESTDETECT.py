import torch
import cv2
import numpy as np

model = torch.jit.load("best.pt")

cap = cv2.VideoCapture(0)

while True:
    ret, frame = cap.read()
    
    frame = cv2.cvtColor(frame, cv2.COLOR_BGR2RGB)
    frame = cv2.resize(frame, (416, 416))
    frame = np.transpose(frame, (2, 0, 1))
    frame = torch.from_numpy(frame).float()
    frame /= 255.0
    
    outputs = model(frame)
    
    for box in outputs[0]['boxes']:
        x1, y1, x2, y2 = box.tolist()
        cv2.rectangle(frame, (int(x1), int(y1)), (int(x2), int(y2)), (0, 255, 0), 2)

    cv2.imshow("Frame", frame)
    if cv2.waitKey(1) & 0xFF == ord('q'):
        break
